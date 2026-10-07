<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use App\Models\AttendanceSession;

class BerandaController extends Controller
{
    public function index()
    {
        $today = now()->today();

        $session = AttendanceSession::whereDate('date', $today)->first();

        $totalSiswa = Student::count();

        if ($session) {
            $summary = $session->summary;
            $hadir = $summary['hadir'];
            $sakit = $summary['sakit'];
            $izin   = $summary['izin'];
            $alpa   = $summary['alpha'];
            $checked = $hadir + $sakit + $izin + $alpa;
        } else {
            $hadir = $sakit = $izin = $alpa = 0;
            $checked = 0;
        }

        $belumDicek = $totalSiswa - $checked;

        if ($session) {
            $students = Student::with(['attendances' => function($q) use ($session) {
                $q->where('session_id', $session->id);
            }])->orderBy('absent_number')->get();

            $absentList = $students->filter(function($s) {
                $att = $s->attendances->first();
                if (!$att) return true;
                return $att->status !== 'hadir';
            })->map(function($s) {
                return [
                    'inisial' => strtoupper(substr($s->name, 0, 2)),
                    'nama'    => $s->name,
                    'nis'     => $s->nis,
                    'status'  => optional($s->attendances->first())->status ?? 'belum',
                ];
            })->values();
        } else {
            $absentList = collect();
        }

        return view('beranda.index', [
            'tanggal'    => now()->locale('id')->isoFormat('dddd, D MMMM Y'),
            'hadir'      => str_pad($hadir, 2, '0', STR_PAD_LEFT),
            'sakit'      => str_pad($sakit, 2, '0', STR_PAD_LEFT),
            'izin'       => str_pad($izin, 2, '0', STR_PAD_LEFT),
            'alpa'       => str_pad($alpa, 2, '0', STR_PAD_LEFT),
            'belumDicek' => $belumDicek,
            'totalSiswa' => $totalSiswa,
            'absentList' => $absentList,
            'noSession'  => !$session,
        ]);
    }
}
