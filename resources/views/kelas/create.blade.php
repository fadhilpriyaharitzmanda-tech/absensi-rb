@extends('layouts.app')

@section('title', 'Tambah Siswa — Presensi 11 RPL B')
@section('page-title', 'Tambah Siswa')
@section('mobile-title', 'Tambah Siswa')
@section('mobile-sub', 'Data Siswa Baru')

@section('content')
<a href="{{ route('kelas') }}" class="back-btn">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    Kembali
</a>

<div class="form-card">
    <form action="{{ route('kelas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group" style="text-align:center">
            <label for="photo" class="photo-upload" id="photoPreview" style="margin:0 auto">
                <div class="photo-upload-placeholder" id="placeholderIcon">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <br><span>Foto</span>
                </div>
            </label>
            <input type="file" id="photo" name="photo" accept="image/*" style="display:none" onchange="previewPhoto(event)">
            @error('photo') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="name">Nama Lengkap</label>
            <input type="text" id="name" name="name" class="form-input @error('name') form-input-error @enderror"
                placeholder="Masukkan nama siswa" value="{{ old('name') }}" required>
            @error('name') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="nis">NIS</label>
            <input type="text" id="nis" name="nis" class="form-input @error('nis') form-input-error @enderror"
                placeholder="Contoh: 6601" value="{{ old('nis') }}" required>
            @error('nis') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="absent_number">No. Absen</label>
            <input type="number" id="absent_number" name="absent_number" class="form-input @error('absent_number') form-input-error @enderror"
                placeholder="Contoh: 1" value="{{ old('absent_number') }}" required>
            @error('absent_number') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn-simpan">SIMPAN</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
function previewPhoto(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('photoPreview');
        const placeholder = document.getElementById('placeholderIcon');
        placeholder.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover">';
    };
    reader.readAsDataURL(file);
}
</script>
@endpush
