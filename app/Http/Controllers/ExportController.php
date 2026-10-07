<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportController extends Controller
{
    private function getRekapData($startMonth, $endMonth, $year)
    {
        $allStudents = Student::orderBy('absent_number')->get();

        $attendanceCounts = Attendance::query()
            ->whereHas('session', fn($sq) => $sq->whereYear('date', $year)
                ->whereMonth('date', '>=', $startMonth)
                ->whereMonth('date', '<=', $endMonth)
            )
            ->selectRaw('student_id, status, count(*) as total')
            ->groupBy('student_id', 'status')
            ->get()
            ->groupBy('student_id');

        $students = $allStudents->map(function ($s) use ($attendanceCounts) {
            $counts = $attendanceCounts->get($s->id, collect())->pluck('total', 'status');
            $hadir = (int) ($counts['hadir'] ?? 0);
            $sakit = (int) ($counts['sakit'] ?? 0);
            $izin  = (int) ($counts['izin'] ?? 0);
            $alpha = (int) ($counts['alpha'] ?? 0);
            $total = $hadir + $sakit + $izin + $alpha;
            $pct   = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

            return (object) [
                'absent_number' => $s->absent_number,
                'name'          => $s->name,
                'nis'           => $s->nis,
                'hadir'         => $hadir,
                'sakit'         => $sakit,
                'izin'          => $izin,
                'alpha'         => $alpha,
                'total'         => $total,
                'pct'           => $pct,
            ];
        });

        $totalSessions = AttendanceSession::whereYear('date', $year)
            ->whereMonth('date', '>=', $startMonth)
            ->whereMonth('date', '<=', $endMonth)
            ->count();

        return compact('students', 'totalSessions');
    }

    private function parsePeriod(Request $request): array
    {
        $year = (int) $request->get('tahun', now()->year);
        if ($year < 2000 || $year > 2100) {
            $year = (int) now()->year;
        }

        $semester = $request->get('semester');
        if (!in_array($semester, ['ganjil', 'genap'], true)) {
            $semester = null;
        }

        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        if ($semester === 'ganjil') {
            return [
                'startMonth' => 7, 'endMonth' => 12, 'year' => $year,
                'semester' => 'ganjil', 'bulan' => null,
                'label' => "Semester Ganjil {$year}/" . ($year + 1),
            ];
        }

        if ($semester === 'genap') {
            return [
                'startMonth' => 1, 'endMonth' => 6, 'year' => $year,
                'semester' => 'genap', 'bulan' => null,
                'label' => "Semester Genap " . ($year - 1) . "/{$year}",
            ];
        }

        $month = (int) $request->get('bulan', now()->month);
        if ($month < 1 || $month > 12) {
            $month = (int) now()->month;
        }

        return [
            'startMonth' => $month, 'endMonth' => $month, 'year' => $year,
            'semester' => null, 'bulan' => $month,
            'label' => $bulanList[$month] . ' ' . $year,
            'bulanList' => $bulanList,
        ];
    }

    public function rekap(Request $request)
    {
        $period = $this->parsePeriod($request);
        $data   = $this->getRekapData($period['startMonth'], $period['endMonth'], $period['year']);

        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return view('export.rekap', array_merge($data, [
            'period'    => $period,
            'month'     => $period['bulan'] ?? now()->month,
            'year'      => $period['year'],
            'bulanList' => $bulanList,
        ]));
    }

    public function rekapExcel(Request $request)
    {
        $period = $this->parsePeriod($request);
        $data   = $this->getRekapData($period['startMonth'], $period['endMonth'], $period['year']);
        $students = $data['students'];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $title = "Rekap Presensi - {$period['label']}";
        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(30);

        $headers = ['No. Absen', 'Nama Siswa', 'NIS', 'Hadir', 'Sakit', 'Izin', 'Alpha', 'Total', 'Kehadiran'];
        $colLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

        foreach ($headers as $i => $header) {
            $col = $colLetters[$i];
            $sheet->setCellValue($col . '3', $header);
            $sheet->getStyle($col . '3')->getFont()->setBold(true);
            $sheet->getStyle($col . '3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($col . '3')->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFE0E7FF');
            $sheet->getStyle($col . '3')->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN);
        }

        $row = 4;
        foreach ($students as $s) {
            $sheet->setCellValue('A' . $row, $s->absent_number);
            $sheet->setCellValue('B' . $row, $s->name);
            $sheet->setCellValue('C' . $row, $s->nis);
            $sheet->setCellValue('D' . $row, $s->hadir);
            $sheet->setCellValue('E' . $row, $s->sakit);
            $sheet->setCellValue('F' . $row, $s->izin);
            $sheet->setCellValue('G' . $row, $s->alpha);
            $sheet->setCellValue('H' . $row, $s->total);
            $sheet->setCellValue('I' . $row, $s->pct . '%');

            $sheet->getStyle('A' . $row . ':I' . $row)->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle('A' . $row . ':I' . $row)->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $row)->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_LEFT);

            $row++;
        }

        foreach ($colLetters as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = "rekap-presensi-{$period['label']}.xlsx";
        $filename = str_replace(['/', ' '], '-', $filename);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function rekapPdf(Request $request)
    {
        $period = $this->parsePeriod($request);
        $data   = $this->getRekapData($period['startMonth'], $period['endMonth'], $period['year']);

        $pdf = Pdf::loadView('export.rekap-pdf', [
            'students'      => $data['students'],
            'totalSessions' => $data['totalSessions'],
            'period'        => $period,
        ]);
        $pdf->setPaper('A4', 'landscape');

        $filename = "rekap-presensi-{$period['label']}.pdf";
        $filename = str_replace(['/', ' '], '-', $filename);

        return $pdf->download($filename);
    }
}
