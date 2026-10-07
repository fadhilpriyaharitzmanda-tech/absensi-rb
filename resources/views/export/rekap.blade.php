@extends('layouts.app')

@section('title', 'Rekap Presensi — Presensi 11 RPL B')
@section('page-title', 'Rekap Presensi')
@section('mobile-title', 'Rekap Presensi')
@section('mobile-sub', $period['semester'] ? 'Semester ' . ucfirst($period['semester']) : $bulanList[$month] . ' ' . $year)

@section('content')

@if(session('error'))
<div class="alert alert-error">{{ session('error') }}</div>
@endif
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Header --}}
<div class="rekap-header">
    <div>
        <div class="section-title" style="margin-bottom:2px">Rekap Presensi Siswa</div>
        <div class="section-subtitle">{{ $period['label'] }} &bull; {{ $totalSessions }} pertemuan</div>
    </div>
    @if(auth()->user()?->canDownloadLaporan())
    <div class="rekap-actions">
        <a href="{{ route('rekap.excel', request()->query()) }}" class="btn-icon-label" title="Download Excel">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Excel</span>
        </a>
        <a href="{{ route('rekap.pdf', request()->query()) }}" class="btn-icon-label primary" title="Download PDF">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>PDF</span>
        </a>
    </div>
    @endif
</div>

{{-- Filter bar --}}
<div class="rekap-filter-bar">
    <div class="filter-tabs">
        @php $thn = date('Y'); @endphp
        <a href="{{ route('rekap', ['semester' => 'ganjil', 'tahun' => $thn]) }}"
           class="filter-tab {{ $period['semester'] === 'ganjil' ? 'active' : '' }}">
            Ganjil {{ $thn }}/{{ $thn + 1 }}
        </a>
        <a href="{{ route('rekap', ['semester' => 'genap', 'tahun' => $thn + 1]) }}"
           class="filter-tab {{ $period['semester'] === 'genap' ? 'active' : '' }}">
            Genap {{ $thn }}/{{ $thn + 1 }}
        </a>
        <a href="{{ route('rekap') }}"
           class="filter-tab {{ !$period['semester'] ? 'active' : '' }}">
            Bulanan
        </a>
    </div>

    <form method="GET" action="{{ route('rekap') }}" class="filter-form {{ !$period['semester'] ? 'visible' : '' }}">
        <select name="bulan" class="form-input">
            @foreach($bulanList as $val => $label)
            <option value="{{ $val }}" {{ (int)$month === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <select name="tahun" class="form-input" style="width:auto">
            @foreach(range(2026, 2028) as $t)
            <option value="{{ $t }}" {{ (int)$year === $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary" style="padding:8px 16px;font-size:13px">Tampilkan</button>
    </form>
</div>

{{-- Table --}}
<div class="table-wrap">
    <table class="rekap-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Siswa</th>
                <th>NIS</th>
                <th>Hadir</th>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Alpha</th>
                <th>Total</th>
                <th>Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $s)
            <tr>
                <td>{{ $s->absent_number }}</td>
                <td class="td-name">{{ $s->name }}</td>
                <td>{{ $s->nis }}</td>
                <td><span class="chip chip-hadir">{{ $s->hadir }}</span></td>
                <td><span class="chip chip-sakit">{{ $s->sakit }}</span></td>
                <td><span class="chip chip-izin">{{ $s->izin }}</span></td>
                <td><span class="chip chip-alpha">{{ $s->alpha }}</span></td>
                <td class="td-total">{{ $s->total }}</td>
                <td class="td-pct" style="color:{{ $s->pct >= 90 ? 'var(--primary)' : ($s->pct >= 75 ? '#ca8a04' : 'var(--error)') }}">{{ $s->pct }}%</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="td-empty">Belum ada data presensi untuk periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
