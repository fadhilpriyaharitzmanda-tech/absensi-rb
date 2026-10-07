@extends('layouts.app')

@section('title', 'Edit Siswa — Presensi 11 RPL B')
@section('page-title', 'Edit Siswa')
@section('mobile-title', 'Edit Siswa')
@section('mobile-sub', $student->name)

@section('content')
<a href="{{ route('kelas.detail', $student->slug) }}" class="back-btn">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    Kembali
</a>

<div class="form-card">
    <form action="{{ route('kelas.update', $student->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group" style="text-align:center">
            <label for="photo" class="photo-upload" id="photoPreview" style="margin:0 auto">
                <div class="photo-upload-placeholder" id="placeholderIcon">
                    @if($student->photo)
                        <img src="{{ asset('storage/'.$student->photo) }}" style="width:100%;height:100%;object-fit:cover">
                    @else
                        <div class="avatar-default">{{ strtoupper(substr($student->name, 0, 1)) }}</div>
                    @endif
                </div>
            </label>
            <input type="file" id="photo" name="photo" accept="image/*" style="display:none" onchange="previewPhoto(event)">
            @error('photo') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="name">Nama Lengkap</label>
            <input type="text" id="name" name="name" class="form-input @error('name') form-input-error @enderror"
                value="{{ old('name', $student->name) }}" required>
            @error('name') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="nis">NIS</label>
            <input type="text" id="nis" name="nis" class="form-input @error('nis') form-input-error @enderror"
                value="{{ old('nis', $student->nis) }}" required>
            @error('nis') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="absent_number">No. Absen</label>
            <input type="number" id="absent_number" name="absent_number" class="form-input @error('absent_number') form-input-error @enderror"
                value="{{ old('absent_number', $student->absent_number) }}" required>
            @error('absent_number') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn-simpan">SIMPAN PERUBAHAN</button>
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
        preview.style.borderRadius = 'var(--radius-full)';
    };
    reader.readAsDataURL(file);
}
</script>
@endpush
