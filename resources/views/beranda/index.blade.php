@extends('layouts.app')

@section('title', 'Beranda — Presensi 11 RPL B')
@section('page-title', 'Beranda')
@section('mobile-title', 'Presensi 11 RPL B')
@section('mobile-sub', 'Daftar Siswa')

@section('content')

{{-- Header info --}}
<div class="mb-4 hide-mobile">
    <div class="section-title">Ringkasan Kehadiran</div>
    <div class="section-subtitle">{{ $tanggal ?? 'Senin, 23 Oktober 2023' }}</div>
</div>

{{-- Stats Row --}}
<div class="grid-4 mb-4">
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div class="stat-label">Hadir</div>
            <div class="stat-icon" style="background:var(--surface-container)">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="var(--primary)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <div class="stat-value primary">{{ $hadir ?? '0' }}</div>
        <div class="label-caps">Siswa</div>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div class="stat-label">Sakit</div>
            <div class="stat-icon" style="background:#fce7f3">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#be185d" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
        </div>
        <div class="stat-value" style="color:#be185d">{{ $sakit ?? '0' }}</div>
        <div class="label-caps">Siswa</div>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div class="stat-label">Izin</div>
            <div class="stat-icon" style="background:#fef9c3">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#854d0e" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
        </div>
        <div class="stat-value" style="color:#854d0e">{{ $izin ?? '0' }}</div>
        <div class="label-caps">Siswa</div>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div class="stat-label">Alpa</div>
            <div class="stat-icon" style="background:var(--error-container)">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="var(--error)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <div class="stat-value error">{{ $alpa ?? '0' }}</div>
        <div class="label-caps">Siswa</div>
    </div>
</div>

@if(auth()->user()?->canInputPresensi())
{{-- CTA Banner --}}
<div class="cta-banner mb-4">
    <div class="cta-title">Catat Presensi Hari Ini</div>
    <div class="cta-sub">Tersisa {{ $belumDicek ?? '4' }} siswa lagi untuk dicek</div>
    <a href="{{ route('harian') }}" class="cta-btn">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        MULAI MENCATAT
    </a>
</div>
@endif

{{-- Siswa Tidak Masuk --}}
<div class="card">
    <div class="section-header">
        <div class="fw-700 fs-16">Siswa Tidak Masuk</div>
        @if(auth()->user()?->canInputPresensi())
        <a href="{{ route('harian') }}" class="link-see-all">Atur Presensi</a>
        @endif
    </div>

    @php
    $absentList = $absentList ?? collect();
    @endphp

    @if($noSession ?? false)
    <div style="text-align:center;padding:24px 0;color:var(--on-surface-variant)">
        <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="var(--on-surface-variant)" stroke-width="1.5" style="margin-bottom:8px"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <div style="font-weight:600;font-size:15px">Data absen hari ini belum diisi</div>
        <div style="font-size:13px;margin-top:2px">Silakan isi presensi terlebih dahulu.</div>
    </div>
    @elseif($absentList->isEmpty())
    <div style="text-align:center;padding:24px 0;color:var(--on-surface-variant)">
        <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="var(--primary)" stroke-width="1.5" style="margin-bottom:8px"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div style="font-weight:600;font-size:15px">Semua siswa hadir hari ini!</div>
        <div style="font-size:13px;margin-top:2px">Tidak ada siswa yang tidak masuk.</div>
    </div>
    @else
    @foreach($absentList as $act)
    <div class="activity-item">
        <div class="avatar-initials">{{ $act['inisial'] }}</div>
        <div class="activity-info">
            <div class="activity-name">{{ $act['nama'] }}</div>
            <div class="activity-nis">NIS: {{ $act['nis'] }}</div>
        </div>
        <div class="activity-meta">
            @if($act['status'] === 'belum')
            <span class="chip chip-belum">BELUM</span>
            @else
            <span class="chip chip-{{ $act['status'] }}">{{ strtoupper($act['status']) }}</span>
            @endif
        </div>
    </div>
    @endforeach
    @endif
</div>

@endsection

@section('fab')
@if(auth()->user()?->canInputPresensi())
<button class="fab hide-desktop" onclick="window.location='{{ route('harian') }}'">
    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
</button>
@endif
@endsection