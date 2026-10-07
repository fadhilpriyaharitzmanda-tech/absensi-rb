@extends('layouts.app')

@section('title', 'Detail Riwayat — Presensi 11 RPL B')
@section('page-title', 'Detail Riwayat')
@section('mobile-title', 'Detail Riwayat')
@section('mobile-sub', \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM Y'))

@section('content')
<a href="{{ route('riwayat') }}" class="back-btn">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    <span class="back-label">Kembali</span>
</a>

<div class="mb-4">
    <div class="section-title">
        {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}
    </div>
    <div class="section-subtitle">
        Presensi Harian &bull; Pertemuan ke-{{ $session->meeting_number }}
    </div>
</div>

{{-- Summary --}}
<div class="summary-stats">
    <div class="summary-stat">
        <div class="summary-stat-value" style="color:var(--primary)">{{ $summary['hadir'] }}</div>
        <div class="summary-stat-label">Hadir</div>
    </div>
    <div class="summary-stat">
        <div class="summary-stat-value" style="color:#be185d">{{ $summary['sakit'] }}</div>
        <div class="summary-stat-label">Sakit</div>
    </div>
    <div class="summary-stat">
        <div class="summary-stat-value" style="color:#854d0e">{{ $summary['izin'] }}</div>
        <div class="summary-stat-label">Izin</div>
    </div>
    <div class="summary-stat">
        <div class="summary-stat-value" style="color:var(--error)">{{ $summary['alpha'] }}</div>
        <div class="summary-stat-label">Alpha</div>
    </div>
</div>

{{-- Student List --}}
<div class="detail-card">
    @foreach($allStudents as $item)
    <div class="student-row">
        <div class="student-avatar">{{ strtoupper(substr($item->student->name, 0, 1)) }}</div>
        <div class="student-info">
            <div class="student-detail-name">{{ $item->student->name }}</div>
            <div class="student-detail-nis">NIS: {{ $item->student->nis }}</div>
        </div>
        <span class="chip chip-{{ $item->status }}">{{ strtoupper($item->status) }}</span>
    </div>
    @endforeach
</div>

<div class="mt-4" style="text-align:center">
    <a href="{{ route('riwayat') }}" class="btn btn-outline">Kembali ke Riwayat</a>
</div>

@endsection
