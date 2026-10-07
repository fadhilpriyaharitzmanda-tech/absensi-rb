@extends('layouts.app')

@section('title', 'Edit Akun — Presensi 11 RPL B')
@section('page-title', 'Edit Akun')
@section('mobile-title', 'Edit Akun')
@section('mobile-sub', $user->name)

@section('content')
<a href="{{ route('admin.users.index') }}" class="back-btn">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    Kembali
</a>

<div class="form-card">
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label" for="name">Nama Lengkap</label>
            <input type="text" id="name" name="name" class="form-input @error('name') form-input-error @enderror"
                placeholder="Masukkan nama" value="{{ old('name', $user->name) }}" required>
            @error('name') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="username">Username</label>
            <input type="text" id="username" name="username" class="form-input @error('username') form-input-error @enderror"
                placeholder="Masukkan username" value="{{ old('username', $user->username) }}" required>
            @error('username') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password <span style="font-weight:400;text-transform:none;color:var(--on-surface-variant)">(kosongkan jika tidak diubah)</span></label>
            <input type="password" id="password" name="password" class="form-input @error('password') form-input-error @enderror"
                placeholder="Minimal 6 karakter">
            @error('password') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="role">Role / Hak Akses</label>
            <select id="role" name="role" class="form-input @error('role') form-input-error @enderror" required>
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="guru" {{ old('role', $user->role) === 'guru' ? 'selected' : '' }}>Guru</option>
                <option value="pengurus_kelas" {{ old('role', $user->role) === 'pengurus_kelas' ? 'selected' : '' }}>Pengurus Kelas</option>
            </select>
            @error('role') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn-simpan">SIMPAN</button>
    </form>
</div>
@endsection
