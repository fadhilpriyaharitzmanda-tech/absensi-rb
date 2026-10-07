<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'semua');

        $query = Student::query();

        if ($filter === 'sempurna') {
            $query->whereDoesntHave('attendances', fn($q) =>
                $q->whereIn('status', ['alpha', 'sakit', 'izin'])
            );
        }

        $siswaList = $query->orderBy('absent_number')->get()->map(fn($s) => [
            'id'     => $s->id,
            'slug'   => $s->slug,
            'nama'   => $s->name,
            'nis'    => $s->nis,
            'foto'   => $s->photo,
        ]);

        return view('kelas.index', compact('filter', 'siswaList'));
    }

    public function show($slug)
    {
        $student = Student::where('slug', $slug)->firstOrFail();

        $now = now();
        $month = $now->month;
        $year  = $now->year;

        $statusCounts = $student->attendances()
            ->whereHas('session', fn($q) =>
                $q->whereMonth('date', $month)->whereYear('date', $year)
            )
            ->selectRaw("status, count(*) as total")
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $hadirMonth = $statusCounts['hadir'] ?? 0;
        $sakit = $statusCounts['sakit'] ?? 0;
        $izin   = $statusCounts['izin'] ?? 0;
        $alpa   = $statusCounts['alpha'] ?? 0;
        $totalMonth = $hadirMonth + $sakit + $izin + $alpa;
        $pct = $totalMonth > 0 ? round(($hadirMonth / $totalMonth) * 100, 1) : 0;

        $prevMonth = $month > 1 ? $month - 1 : 12;
        $prevYear  = $month > 1 ? $year : $year - 1;
        $prevPct   = $student->getAttendancePercentage($prevMonth, $prevYear);
        $growth    = $prevPct > 0 ? round($pct - $prevPct, 1) : 0;

        $riwayat = $student->attendances()
            ->with('session')
            ->whereIn('status', ['sakit', 'izin', 'alpha'])
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn($a) => [
                'tanggal'    => $a->session->date->locale('id')->isoFormat('dddd, D MMMM Y'),
                'waktu'      => $a->created_at->format('H:i') . ' WIB',
                'status'     => $a->status,
                'keterangan' => $a->note,
            ]);

        $siswa = [
            'id'            => $student->id,
            'slug'          => $student->slug,
            'nama'          => $student->name,
            'nis'           => $student->nis,
            'absent_number' => $student->absent_number,
            'foto'          => $student->photo,
            'pct'           => $pct,
            'pct_growth'    => $growth,
            'sakit'         => $sakit,
            'izin'          => $izin,
            'alpa'          => $alpa,
        ];

        return view('kelas.detail', compact('siswa', 'riwayat'));
    }

    public function create()
    {
        return view('kelas.create');
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('kelas.edit', compact('student'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:100',
            'nis'           => 'required|string|unique:students,nis',
            'absent_number' => 'required|integer|unique:students,absent_number',
            'photo'         => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        Student::create($data);

        return redirect()->route('kelas')->with('success', 'Siswa berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $data = $request->validate([
            'name'          => 'required|string|max:100',
            'nis'           => 'required|string|unique:students,nis,' . $id,
            'absent_number' => 'required|integer|unique:students,absent_number,' . $id,
            'photo'         => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                Storage::disk('public')->delete($student->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $student->update($data);

        return redirect()->route('kelas.detail', $student->slug)->with('success', 'Data siswa diperbarui!');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        if ($student->photo && Storage::disk('public')->exists($student->photo)) {
            Storage::disk('public')->delete($student->photo);
        }
        $student->delete();

        return redirect()->route('kelas')->with('success', 'Siswa berhasil dihapus.');
    }

    public function riwayat($id)
    {
        $student = Student::findOrFail($id);
        $riwayat = $student->attendances()
            ->with('session')
            ->latest()
            ->paginate(20);

        return view('kelas.riwayat', compact('student', 'riwayat'));
    }
}