@extends('layouts.app')

@section('title', 'Data Kelas — Presensi 11 RPL B')
@section('page-title', 'Data Kelas 11 RPL B')
@section('mobile-title', 'Kehadiran 11 RPL B')
@section('mobile-sub', 'Daftar Siswa')

@section('content')

{{-- Search --}}
<div class="search-input-wrap mb-3">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/></svg>
    <input type="text" class="search-input" placeholder="Cari berdasarkan nama atau NIS..."
        oninput="filterSiswa(this.value)" id="searchInput">
</div>

{{-- Filter Tabs --}}
<div class="filter-scroll mb-4">
    <div class="filter-tabs-inner">
        <a href="{{ route('kelas', ['filter'=>'semua']) }}" class="filter-tab {{ ($filter ?? 'semua') === 'semua' ? 'active' : '' }}">Semua Siswa</a>
        <a href="{{ route('kelas', ['filter'=>'sempurna']) }}" class="filter-tab {{ ($filter ?? '') === 'sempurna' ? 'active' : '' }}">Kehadiran Sempurna</a>
    </div>
</div>

{{-- Count --}}
<div class="flex items-center justify-between mb-3">
    <div class="label-caps">{{ count($siswaList ?? []) }} Siswa</div>
    @if(auth()->user()?->isAdmin())
    <button onclick="showModal('modalTambah')" class="filter-link">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Tambah
    </button>
    @endif
</div>

{{-- Student List --}}
<div class="students-grid" id="studentList">

    @php
    $siswaList = $siswaList ?? [];
    @endphp

    @forelse($siswaList as $siswa)
    <div class="student-card" data-name="{{ strtolower($siswa['nama']) }}" data-nis="{{ $siswa['nis'] }}">
        <div class="student-card-photo">
            @if($siswa['foto'])
                <img src="{{ asset('storage/'.$siswa['foto']) }}" alt="{{ $siswa['nama'] }}">
            @else
                <div style="width:100%;height:100%;border-radius:var(--radius-lg);background:var(--surface-container-high);display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:700;color:var(--primary)">
                    {{ strtoupper(substr($siswa['nama'],0,1)) }}
                </div>
                @endif
        </div>

        <div class="student-card-info">
            <a href="{{ route('kelas.detail', $siswa['slug']) }}" class="student-card-name">{{ $siswa['nama'] }}</a>
            <div class="student-card-nis">NIS: {{ $siswa['nis'] }}</div>
        </div>

        <div class="student-card-actions">
            <a href="{{ route('kelas.detail', $siswa['slug']) }}" class="card-action-btn" title="Detail">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </a>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <div class="empty-state-icon">🔍</div>
        <div style="font-weight:600;margin-bottom:4px">Tidak ada siswa ditemukan</div>
        <div style="font-size:13px">Coba ubah kata kunci pencarian</div>
    </div>
    @endforelse
</div>

{{-- Modal Tambah --}}
<div class="modal-overlay" id="modalTambah" onclick="if(event.target===this)hideModal('modalTambah')">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title">Tambah Siswa</div>
            <button class="modal-close" onclick="hideModal('modalTambah')">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('kelas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group" style="text-align:center">
                <label for="photoTambah" class="photo-upload" id="photoPreviewTambah" style="margin:0 auto">
                    <div class="photo-upload-placeholder" id="placeholderTambah">
                        <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <br><span>Foto</span>
                    </div>
                </label>
                <input type="file" id="photoTambah" name="photo" accept="image/*" style="display:none" onchange="previewModalPhoto(event, 'photoPreviewTambah', 'placeholderTambah')">
            </div>
            <div class="form-group">
                <label class="form-label" for="nameTambah">Nama Lengkap</label>
                <input type="text" id="nameTambah" name="name" class="form-input" placeholder="Masukkan nama siswa" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="nisTambah">NIS</label>
                <input type="text" id="nisTambah" name="nis" class="form-input" placeholder="Contoh: 22.110.042" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="absentNumberTambah">No. Absen</label>
                <input type="number" id="absentNumberTambah" name="absent_number" class="form-input" placeholder="Contoh: 1" required>
            </div>
            <button type="submit" class="btn-simpan">SIMPAN</button>
        </form>
    </div>
</div>

@endsection

@section('fab')
@if(auth()->user()?->isAdmin())
<button class="fab hide-desktop" onclick="showModal('modalTambah')">
    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
</button>

{{-- Desktop Add button --}}
<div class="hide-mobile" style="margin-top:24px">
    <button onclick="showModal('modalTambah')" class="btn btn-primary" style="width:auto;display:inline-flex">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Tambah Siswa
    </button>
</div>
@endif
@endsection

@push('scripts')
<script>
function filterSiswa(query) {
    const cards = document.querySelectorAll('.student-card');
    const q = query.toLowerCase();
    cards.forEach(card => {
        const name = card.dataset.name;
        const nis  = card.dataset.nis;
        card.style.display = (name.includes(q) || nis.includes(q)) ? '' : 'none';
    });
}
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