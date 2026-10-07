@extends('layouts.app')

@section('title', 'Riwayat ' . $student->name . ' — Presensi 11 RPL B')
@section('page-title', 'Riwayat Presensi')
@section('mobile-title', 'Riwayat Presensi')
@section('mobile-sub', $student->name)

@section('content')
<a href="{{ route('kelas.detail', $student->slug) }}" class="back-btn">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    <span class="back-label">Kembali</span>
</a>

<div class="mb-4">
    <div class="section-title">Riwayat {{ $student->name }}</div>
    <div class="section-subtitle">NIS: {{ $student->nis }} &bull; Absen: {{ $student->absent_number }}</div>
</div>

<div class="flex-col-gap-3" style="gap:8px">
    @forelse($riwayat as $rec)
    <div class="riwayat-item">
        <div class="riwayat-left">
            <div class="riwayat-icon">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <div class="riwayat-date">{{ $rec->session->date->locale('id')->isoFormat('dddd, D MMMM Y') }}</div>
                <div class="riwayat-time">{{ $rec->created_at->format('H:i') }} WIB</div>
                @if($rec->note)
                <div class="riwayat-note">{{ $rec->note }}</div>
                @endif
            </div>
        </div>
        <span class="chip chip-{{ $rec->status }}">{{ strtoupper($rec->status) }}</span>
    </div>
    @empty
    <div class="empty-state">
        <div style="font-size:36px;margin-bottom:12px">📋</div>
        <div style="font-weight:600;margin-bottom:4px">Belum ada riwayat presensi</div>
        <div style="font-size:13px">Data akan muncul setelah presensi dilakukan</div>
    </div>
    @endforelse
</div>

@if($riwayat->hasPages())
<div class="pagination-info">
    Halaman {{ $riwayat->currentPage() }} dari {{ $riwayat->lastPage() }}
    @if($riwayat->previousPageUrl())
        <a href="{{ $riwayat->previousPageUrl() }}" class="filter-link" style="margin-left:8px">Sebelumnya</a>
    @endif
    @if($riwayat->nextPageUrl())
        <a href="{{ $riwayat->nextPageUrl() }}" class="filter-link" style="margin-left:8px">Selanjutnya</a>
    @endif
</div>
@endif

@endsection
