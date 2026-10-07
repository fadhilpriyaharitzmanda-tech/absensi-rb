<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HarianController extends Controller
{
    public function index()
    {
        $today = now()->today();

        $session = AttendanceSession::whereDate('date', $today)->first();

        $students = Student::orderBy('absent_number')->get();

        $existingAttendances = collect();
        if ($session) {
            $existingAttendances = $session->attendances()
                ->pluck('status', 'student_id');
        }

        $siswaList = $students->map(fn($s) => [
            'id'     => $s->id,
            'nama'   => $s->name,
            'kode'   => 'RPL-' . str_pad($s->absent_number, 5, '0', STR_PAD_LEFT),
            'foto'   => $s->photo,
            'status' => $existingAttendances->get($s->id, 'hadir'),
        ]);

        $totalSiswa = $students->count();
        $selesai = $existingAttendances->count();

        return view('harian.index', [
            'tanggal'    => now()->locale('id')->isoFormat('dddd, D MMM Y'),
            'totalSiswa' => $totalSiswa,
            'selesai'    => $selesai,
            'siswaList'  => $siswaList,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'presensi'   => 'required|array',
            'presensi.*' => 'nullable|in:hadir,sakit,izin,alpha',
        ]);

        $today = now()->today();

        DB::transaction(function () use ($data, $today) {
            $session = AttendanceSession::firstOrCreate(
                ['date' => $today],
                [
                    'created_by'     => auth()->id(),
                    'meeting_number' => AttendanceSession::count() + 1,
                    'is_open'        => true,
                ]
            );

            $validStudentIds = Student::pluck('id')->flip()->toArray();
            $rows = [];
            $now = now();

            foreach ($data['presensi'] as $studentId => $status) {
                if (!$status || !isset($validStudentIds[$studentId])) {
                    continue;
                }

                $rows[] = [
                    'session_id' => $session->id,
                    'student_id' => (int) $studentId,
                    'status'     => $status,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if (!empty($rows)) {
                Attendance::upsert($rows, ['session_id', 'student_id'], ['status', 'updated_at']);
            }
        });

        return redirect()->route('beranda')
            ->with('success', 'Presensi berhasil disimpan!');
    }
}
