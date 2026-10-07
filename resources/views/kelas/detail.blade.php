@extends('layouts.app')

@section('title', ($siswa['nama'] ?? 'Detail Siswa') . ' — Presensi 11 RPL B')
@section('page-title', 'Detail Siswa')
@section('mobile-title', 'Detail Siswa')
@section('mobile-sub', '')

@section('content')

{{-- Back (mobile) --}}
<a href="{{ route('kelas') }}" class="back-btn">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    <span class="back-label">Kembali</span>
</a>

@php
$siswa = $siswa ?? [];
$riwayat = $riwayat ?? [];
@endphp

{{-- Profile --}}
<div class="profile-header">
    <div class="profile-photo">
        @if($siswa['foto'])
            <img src="{{ asset('storage/'.$siswa['foto']) }}" alt="{{ $siswa['nama'] }}" class="obj-cover">
        @else
            {{ strtoupper(substr($siswa['nama'],0,1)) }}
        @endif
    </div>
    <div class="profile-info">
        <div class="profile-name">{{ $siswa['nama'] }}</div>
        <div class="profile-nis-badge">NIS: {{ $siswa['nis'] }}</div>
    </div>
</div>

@if(auth()->user()?->isAdmin())
{{-- Edit & Hapus — hanya admin --}}
<div style="display:flex;gap:8px;margin-bottom:16px">
    <button onclick="showModal('modalEdit')" class="edit-btn" style="margin-bottom:0;flex:1;border:none">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        EDIT PROFIL
    </button>
    <form action="{{ route('kelas.destroy', $siswa['id']) }}" method="POST" onsubmit="return confirm('Yakin hapus siswa {{ $siswa['nama'] }}? Semua data presensinya akan ikut terhapus.')" style="flex:1">
        @csrf @method('DELETE')
        <button type="submit" class="edit-btn" style="margin-bottom:0;width:100%;background:transparent;color:var(--error);border:1.5px solid var(--error)">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            HAPUS
        </button>
    </form>
</div>
@endif

{{-- Modal Edit --}}
<div class="modal-overlay" id="modalEdit" onclick="if(event.target===this)hideModal('modalEdit')">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title">Edit {{ $siswa['nama'] }}</div>
            <button class="modal-close" onclick="hideModal('modalEdit')">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('kelas.update', $siswa['id']) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-group" style="text-align:center">
                <label for="photoEdit" class="photo-upload" id="photoPreviewEdit" style="margin:0 auto;border-radius:var(--radius-full)">
                    <div class="photo-upload-placeholder" id="placeholderEdit">
                        @if($siswa['foto'])
                            <img src="{{ asset('storage/'.$siswa['foto']) }}" class="obj-cover">
                        @else
                            <div class="avatar-default">{{ strtoupper(substr($siswa['nama'], 0, 1)) }}</div>
                        @endif
                    </div>
                </label>
                <input type="file" id="photoEdit" name="photo" accept="image/*" style="display:none" onchange="previewModalPhoto(event, 'photoPreviewEdit', 'placeholderEdit')">
            </div>
            <div class="form-group">
                <label class="form-label" for="nameEdit">Nama Lengkap</label>
                <input type="text" id="nameEdit" name="name" class="form-input" value="{{ $siswa['nama'] }}" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="nisEdit">NIS</label>
                <input type="text" id="nisEdit" name="nis" class="form-input" value="{{ $siswa['nis'] }}" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="absentNumberEdit">No. Absen</label>
                <input type="number" id="absentNumberEdit" name="absent_number" class="form-input" value="{{ $siswa['absent_number'] }}" required>
            </div>
            <button type="submit" class="btn-simpan">SIMPAN PERUBAHAN</button>
        </form>
    </div>
</div>

{{-- Stats --}}
<div class="detail-stats">

    {{-- Attendance percentage --}}
    <div class="pct-card">
        <div>
            <div class="pct-label">Persentase Kehadiran</div>
            <div class="pct-value">{{ number_format($siswa['pct'], 1) }}%</div>
            <div class="pct-growth">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                {{ $siswa['pct_growth'] }}% dari bulan lalu
            </div>
        </div>
        <div class="mini-chart">
            @foreach([40,55,60,70,75,80,95] as $h)
            <div class="mini-bar {{ $h >= 90 ? 'active' : ($h < 50 ? 'low' : '') }}" style="height:{{ $h/2 }}px"></div>
            @endforeach
        </div>
    </div>

    {{-- Sakit & Izin --}}
    <div class="absence-grid">
        <div class="absence-card">
            <div class="label-caps">Sakit</div>
            <div class="absence-value">
                <span class="absence-num">{{ $siswa['sakit'] }}</span>
                <span class="absence-unit">Hari</span>
            </div>
        </div>
        <div class="absence-card">
            <div class="label-caps">Izin</div>
            <div class="absence-value">
                <span class="absence-num">{{ $siswa['izin'] }}</span>
                <span class="absence-unit">Hari</span>
            </div>
        </div>
    </div>

    {{-- Alpa --}}
    <div class="alpha-card">
        <div>
            <div class="label-caps">Alpa (Tanpa Keterangan)</div>
            <div class="alpha-num" style="{{ $siswa['alpa'] === 0 ? 'color:var(--on-surface)' : '' }}">
                {{ $siswa['alpa'] }}
            </div>
        </div>
        @if($siswa['alpa'] === 0)
        <div class="alpha-ok">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        </div>
        @endif
    </div>

</div>

{{-- Riwayat Terakhir --}}
<div class="section-header-row mt-6">
    <div class="fw-700" style="font-size:17px">Riwayat Terakhir</div>

    <a href="{{ route('kelas.riwayat', $siswa['id'] ?? 1) }}" class="link-see-all">Lihat Semua</a>
</div>

<div style="display:flex;flex-direction:column;gap:8px">
    @forelse($riwayat as $rec)
    <div class="history-item">
        <div class="history-icon">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div class="history-info">
            <div class="history-date">{{ $rec['tanggal'] }}</div>
            <div class="history-time">{{ $rec['keterangan'] ?? $rec['waktu'] }}</div>
        </div>
        <span class="chip chip-{{ $rec['status'] }}">{{ strtoupper($rec['status']) }}</span>
    </div>
    @empty
    <div class="empty-state" style="margin:0">
        <div style="font-weight:600;margin-bottom:4px;color:var(--on-surface-variant)">Tidak ada riwayat apapun</div>
    </div>
    @endforelse
</div>

@endsection

@push('scripts')
<script>
function showModal(id) { document.getElementById(id).classList.add('show'); }
function hideModal(id) { document.getElementById(id).classList.remove('show'); }
function previewModalPhoto(event, previewId, placeholderId) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById(placeholderId).innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover">';
    };
    reader.readAsDataURL(file);
}
</script>
@endpush