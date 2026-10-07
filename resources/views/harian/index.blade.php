@extends('layouts.app')

@section('title', 'Input Presensi — Presensi 11 RPL B')
@section('page-title', 'Input Presensi Harian')
@section('mobile-title', 'Input Presensi')
@section('mobile-sub', $tanggal ?? "Senin, 23 Okt 2023")

@section('content')

{{-- Tanggal info --}}
<div class="mb-4 hide-desktop">
    <div style="display:flex;align-items:center;gap:6px;color:var(--on-surface-variant);font-size:13px">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        {{ $tanggal ?? 'Senin, 23 Okt 2023' }}
    </div>
</div>

{{-- Progress cards --}}
<div class="grid-2 mb-4">
    <div class="progress-row">
        <div class="progress-icon-box" style="background:var(--surface-container)">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="var(--primary)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
            <div class="label-caps">Total Siswa</div>
            <div style="font-size:26px;font-weight:700;color:var(--on-surface);line-height:1.1;margin-top:3px">{{ $totalSiswa ?? '32' }}</div>
        </div>
    </div>
    <div class="progress-row">
        <div class="progress-icon-box" style="background:#d1fae5">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#059669" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <div class="label-caps">Selesai</div>
            <div style="font-size:26px;font-weight:700;color:#059669;line-height:1.1;margin-top:3px">{{ $selesai ?? '12' }}/{{ $totalSiswa ?? '32' }}</div>
        </div>
    </div>
</div>

{{-- Search --}}
<div class="search-box mb-4">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="var(--on-surface-variant)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
    <input type="text" id="searchInput" placeholder="Cari nama siswa..." oninput="filterSiswa(this.value)">
</div>

{{-- Student Cards --}}
<form action="{{ route('harian.store') }}" method="POST" id="presensiForm">
    @csrf
    <div class="flex-col-gap-3">

        @php
        $siswaList = $siswaList ?? [];
        @endphp

        @foreach($siswaList as $index => $siswa)
        <div class="student-input-card" id="card-{{ $siswa['id'] }}" data-nama="{{ strtolower($siswa['nama']) }}">
            <div class="student-input-header">
                @if($siswa['foto'])
                    <img src="{{ asset('storage/'.$siswa['foto']) }}" alt="{{ $siswa['nama'] }}" class="student-photo">
                @else
                    <div class="student-photo" style="display:flex;align-items:center;justify-content:center;font-weight:700;font-size:16px;color:var(--primary)">
                        {{ strtoupper(substr($siswa['nama'],0,1)) }}
                    </div>
                @endif
                <div>
                    <div class="student-name">{{ $siswa['nama'] }}</div>
                    <div class="student-id">{{ $siswa['kode'] }}</div>
                </div>
            </div>

            <div class="toggle-group">
                @foreach(['hadir','sakit','izin','alpha'] as $st)
                <button type="button"
                    class="toggle-btn {{ ($siswa['status'] ?? 'hadir') === $st ? 'active-'.$st : '' }}"
                    data-siswa="{{ $siswa['id'] }}"
                    data-status="{{ $st }}"
                    onclick="setStatus({{ $siswa['id'] }}, '{{ $st }}', this)">
                    @if($st === 'hadir')
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    @elseif($st === 'sakit')
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @elseif($st === 'izin')
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    @else
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    @endif
                    <span>{{ strtoupper($st) }}</span>
                </button>
                @endforeach
                <input type="hidden" name="presensi[{{ $siswa['id'] }}]" id="input-{{ $siswa['id'] }}" value="{{ $siswa['status'] ?? 'hadir' }}">
            </div>
        </div>
        @endforeach

    </div>

    {{-- Submit bar --}}
    <div class="submit-bar">
        <button type="submit" class="submit-btn">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            SIMPAN PRESENSI
        </button>
    </div>
</form>

@endsection

@section('fab')
{{-- FAB mobile submit --}}
<button class="fab hide-desktop" onclick="document.getElementById('presensiForm').submit()">
    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
</button>
@endsection

@push('scripts')
<script>
function setStatus(siswaId, status, clickedBtn) {
    const allBtns = document.querySelectorAll(`[data-siswa="${siswaId}"]`);
    allBtns.forEach(b => {
        b.className = b.className.replace(/active-\w+/g, '').trim();
    });

    const input = document.getElementById(`input-${siswaId}`);

    if (status === 'hadir') {
        // Klik hadir = reset ke default (kembali ke hijau)
        clickedBtn.classList.add('active-hadir');
        input.value = 'hadir';
        return;
    }

    // Klik status lain (sakit/izin/alpha)
    clickedBtn.classList.add(`active-${status}`);
    input.value = status;
}

function filterSiswa(query) {
    const keyword = query.toLowerCase().trim();
    document.querySelectorAll('.student-input-card').forEach(card => {
        const nama = card.getAttribute('data-nama');
        card.classList.toggle('hidden', keyword && !nama.includes(keyword));
    });
}
</script>
@endpush