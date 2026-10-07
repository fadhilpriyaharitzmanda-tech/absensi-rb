<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $totalStudents = Student::count();

        $query = AttendanceSession::withCount([
            'attendances as hadir_count' => fn($q) => $q->where('status', 'hadir'),
            'attendances as absen_count' => fn($q) => $q->whereIn('status', ['sakit', 'izin', 'alpha']),
        ]);

        $sessions = $query->latest('date')
            ->paginate(20)
            ->withQueryString()
            ->through(fn($s) => [
                'id'      => $s->id,
                'tgl_num' => $s->date->format('d'),
                'bulan'   => strtoupper($s->date->locale('id')->isoFormat('MMM')),
                'hari'    => $s->date->locale('id')->isoFormat('dddd, D MMMM Y'),
                'hadir'   => $s->hadir_count,
                'absen'   => $s->absen_count,
                'past'    => $s->date->lt(today()),
                'date'    => $s->date->format('Y-m-d'),
            ]);

        $totalSessions = AttendanceSession::count();
        $rataKehadiran = 0;
        if ($totalSessions > 0 && $totalStudents > 0) {
            $allHadir = Attendance::where('status', 'hadir')->count();
            $totalPossible = $totalSessions * $totalStudents;
            $rataKehadiran = $totalPossible > 0 ? round(($allHadir / $totalPossible) * 100) : 0;
        }

        // Hitung rata-rata bulan ini vs bulan lalu
        $now = now();
        $bulanAngka = $now->month;
        $tahunAngka = $now->year;
        $bulanLalu = $now->copy()->subMonth()->month;
        $tahunLalu = $now->copy()->subMonth()->year;

        $hitungRata = function ($month, $year) use ($totalStudents) {
            $sessions = AttendanceSession::whereYear('date', $year)
                ->whereMonth('date', $month)->count();
            if ($sessions === 0 || $totalStudents === 0) return 0;
            $hadir = Attendance::where('status', 'hadir')
                ->whereHas('session', fn($q) => $q->whereYear('date', $year)->whereMonth('date', $month))
                ->count();
            return round(($hadir / ($sessions * $totalStudents)) * 100);
        };

        $rataBulanIni = $hitungRata($bulanAngka, $tahunAngka);
        $rataBulanLalu = $hitungRata($bulanLalu, $tahunLalu);
        $changePct = $rataBulanIni - $rataBulanLalu;

        // Cari hari dengan absensi terbanyak (dihitung langsung via agregasi SQL)
        $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu'];
        $dayStats = AttendanceSession::join('attendances', 'attendance_sessions.id', '=', 'attendances.session_id')
            ->selectRaw('DAYOFWEEK(attendance_sessions.date) as day_num,
                         COUNT(*) as total_count,
                         SUM(CASE WHEN attendances.status IN ("sakit", "izin", "alpha") THEN 1 ELSE 0 END) as absent_count')
            ->groupBy('day_num')
            ->get();

        $seringAbsen = 'Senin';
        $seringAbsenRate = 0;
        foreach ($dayStats as $stat) {
            $total = (int) $stat->total_count;
            $absent = (int) $stat->absent_count;
            $rate = $total > 0 ? round(($absent / $total) * 100, 1) : 0;
            if ($rate > $seringAbsenRate) {
                $seringAbsenRate = $rate;
                $dayIndex = (int) $stat->day_num;
                $seringAbsen = $dayNames[$dayIndex - 1] ?? 'Senin';
            }
        }

        return view('riwayat.index', [
            'bulan'           => now()->locale('id')->isoFormat('MMMM'),
            'rataKehadiran'   => $rataKehadiran,
            'rataBulanIni'    => $rataBulanIni,
            'changePct'       => $changePct,
            'seringAbsen'     => $seringAbsen,
            'seringAbsenRate' => $seringAbsenRate,
            'logs'            => $sessions,
        ]);
    }

    public function detail($tanggal)
    {
        $session = AttendanceSession::whereDate('date', $tanggal)->first();

        if (!$session) {
            $hari = \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM Y');
            return redirect()->route('riwayat')->with('error', "Data presensi untuk {$hari} tidak ditemukan.");
        }

        $attendances = $session->attendances()->with('student')->get()->keyBy('student_id');

        $allStudents = $attendances->map(function ($attendance) {
            return (object) [
                'student' => $attendance->student,
                'status'  => $attendance->status,
            ];
        })->sortBy('student.absent_number');

        $summary = [
            'hadir' => $attendances->where('status', 'hadir')->count(),
            'sakit' => $attendances->where('status', 'sakit')->count(),
            'izin'  => $attendances->where('status', 'izin')->count(),
            'alpha' => $attendances->where('status', 'alpha')->count(),
        ];

        return view('riwayat.detail', compact('tanggal', 'session', 'allStudents', 'summary'));
    }
}
