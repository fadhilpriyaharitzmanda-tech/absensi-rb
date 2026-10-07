@extends('layouts.app')

@section('title', 'Riwayat — Presensi 11 RPL B')
@section('mobile-title', 'Riwayat Kehadiran')
@section('mobile-sub', 'Rekap & Riwayat')

@section('content')

@if(session('error'))
<div class="alert alert-error">{{ session('error') }}</div>
@endif
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Rekap Bulanan --}}
<div class="label-caps mb-3 hide-mobile">Rekap Bulanan — {{ $bulan ?? 'Oktober' }}</div>

<div class="rekap-grid">
    <div class="rekap-card">
        <div class="label-caps">Rata-rata Kehadiran</div>
        <div style="font-size:34px;font-weight:700;color:var(--primary);line-height:1.1;margin:6px 0 4px;letter-spacing:-0.02em">
            {{ $rataBulanIni ?? $rataKehadiran ?? 0 }}<span style="font-size:18px">%</span>
        </div>
        <div style="font-size:12px;color:var(--on-surface-variant)">
            @php $change = $changePct ?? 0; @endphp
            @if($change > 0)
                +{{ $change }}% dari bulan lalu
            @elseif($change < 0)
                {{ $change }}% dari bulan lalu
            @else
                Sama dengan bulan lalu
            @endif
        </div>
    </div>
    <div class="rekap-card">
        <div class="label-caps">Sering Absen</div>
        <div style="font-size:24px;font-weight:700;color:var(--error);line-height:1.2;margin:6px 0 4px;">
            {{ $seringAbsen ?? 'Senin' }}
        </div>
        <div style="font-size:12px;color:var(--on-surface-variant)">{{ number_format($seringAbsenRate ?? 0, 1) }}% absen</div>
    </div>
</div>

<div class="search-input-wrap mb-4">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/></svg>
    <input type="text" class="search-input" placeholder="Cari riwayat siswa tertentu..."
        oninput="filterRiwayat(this.value)">
</div>

<div class="flex-col-gap-3" style="gap:10px;margin-bottom:24px" id="logList">

    @php
    $logs = $logs ?? [];
    @endphp

    @foreach($logs as $log)
    <a href="{{ route('riwayat.detail', ['tanggal' => $log['date']]) }}" class="log-item {{ $log['past'] ? 'log-item-past' : '' }}">
        <div class="log-date-badge">
            <div class="month">{{ $log['bulan'] }}</div>
            <div class="day">{{ $log['tgl_num'] }}</div>
        </div>
        <div class="log-info">
            <div class="log-title">{{ $log['hari'] }}</div>
            <div class="log-sub">{{ $log['hadir'] }} hadir · {{ $log['absen'] }} absen</div>
        </div>
        <div class="log-arrow">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </div>
    </a>
    @endforeach
</div>

{{-- Pagination --}}
<div class="pagination-wrap">
    {{ $logs->links('vendor.pagination.custom') }}
</div>

@endsection

@push('scripts')
<script>
function filterRiwayat(query) {
    const items = document.querySelectorAll('#logList .log-item');
    const q = query.toLowerCase();
    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(q) ? '' : 'none';
    });
}
</script>
@endpush