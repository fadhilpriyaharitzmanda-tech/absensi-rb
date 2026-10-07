@extends('layouts.app')

@section('title', 'Kelola User — Presensi 11 RPL B')
@section('page-title', 'Kelola User')
@section('mobile-title', 'Kelola User')
@section('mobile-sub', 'Manajemen Akun')

@section('content')

@if(session('success'))
<div class="alert alert-success" id="alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-error" id="alert-error">{{ session('error') }}</div>
@endif

@if($errors->any())
<div class="alert alert-error">
    @foreach($errors->all() as $err)
    <div>{{ $err }}</div>
    @endforeach
</div>
@endif

<div class="section-header">
    <div class="label-caps">{{ count($users) }} Akun</div>
    <button type="button" onclick="showModal('modal-create')" class="filter-link">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Tambah Akun
    </button>
</div>

<div class="table-wrap mt-3">
    <table class="rekap-table">
        <thead>
            <tr>
                <th style="text-align:left;padding-left:16px">Nama</th>
                <th style="text-align:left;padding-left:16px">Username</th>
                <th>Role</th>
                <th style="width:100px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php
                $roleLabels = ['guru' => 'Guru', 'admin' => 'Admin', 'pengurus_kelas' => 'Pengurus Kelas'];
                $roleColors = ['guru' => 'chip-hadir', 'admin' => 'chip-izin', 'pengurus_kelas' => 'chip-sakit'];
            @endphp
            @forelse($users as $user)
            <tr>
                <td class="td-name" style="padding-left:16px">
                    <div style="display:flex;align-items:center;gap:10px">
                        <div class="avatar-initials" style="width:32px;height:32px;font-size:12px">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:14px">{{ $user->name }}</div>
                        </div>
                    </div>
                </td>
                <td style="font-family:'JetBrains Mono',monospace;font-size:13px;text-align:left;padding-left:16px">{{ $user->username }}</td>
                <td>
                    <span class="chip {{ $roleColors[$user->role] ?? 'chip-belum' }}">
                        {{ $roleLabels[$user->role] ?? $user->role }}
                    </span>
                </td>
                <td>
                    <div style="display:flex;gap:6px;justify-content:center">
                        <button type="button" onclick="openEditModal({{ $user->id }})" class="card-action-btn" title="Edit">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun {{ $user->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="card-action-btn" style="color:var(--error);border-color:var(--error-container)" title="Hapus">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="td-empty">Belum ada user</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="hide-desktop" style="margin-top:16px">
    <button type="button" onclick="showModal('modal-create')" class="btn btn-primary btn-full" style="display:flex;align-items:center;justify-content:center;gap:6px">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Tambah Akun
    </button>
</div>

{{-- ===== MODAL CREATE ===== --}}
<div id="modal-create" class="modal-overlay" onclick="if(event.target===this)hideModal('modal-create')">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title">Tambah Akun</div>
            <button type="button" class="modal-close" onclick="hideModal('modal-create')">&times;</button>
        </div>
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="create-name">Nama Lengkap</label>
                <input type="text" id="create-name" name="name" class="form-input" placeholder="Masukkan nama" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="create-username">Username</label>
                <input type="text" id="create-username" name="username" class="form-input" placeholder="Masukkan username" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="create-password">Password</label>
                <input type="password" id="create-password" name="password" class="form-input" placeholder="Minimal 6 karakter" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="create-role">Role / Hak Akses</label>
                <select id="create-role" name="role" class="form-input" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin">Admin</option>
                    <option value="guru">Guru</option>
                    <option value="pengurus_kelas">Pengurus Kelas</option>
                </select>
            </div>
            <button type="submit" class="btn-simpan">SIMPAN</button>
        </form>
    </div>
</div>

{{-- ===== MODAL EDIT ===== --}}
<div id="modal-edit" class="modal-overlay" onclick="if(event.target===this)hideModal('modal-edit')">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title">Edit Akun</div>
            <button type="button" class="modal-close" onclick="hideModal('modal-edit')">&times;</button>
        </div>
        <form id="form-edit" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label" for="edit-name">Nama Lengkap</label>
                <input type="text" id="edit-name" name="name" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit-username">Username</label>
                <input type="text" id="edit-username" name="username" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit-password">Password <span style="font-weight:400;text-transform:none;color:var(--on-surface-variant)">(kosongkan jika tidak diubah)</span></label>
                <input type="password" id="edit-password" name="password" class="form-input" placeholder="Minimal 6 karakter">
            </div>
            <div class="form-group">
                <label class="form-label" for="edit-role">Role / Hak Akses</label>
                <select id="edit-role" name="role" class="form-input">
                    <option value="admin">Admin</option>
                    <option value="guru">Guru</option>
                    <option value="pengurus_kelas">Pengurus Kelas</option>
                </select>
            </div>
            <button type="submit" class="btn-simpan">SIMPAN</button>
        </form>
    </div>
</div>

@endsection

@section('fab')
<button type="button" class="fab hide-desktop" onclick="showModal('modal-create')">
    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
</button>
@endsection

@push('scripts')
<script>
const users = @json($users);

function showModal(id) {
    document.getElementById(id).classList.add('show');
}
function hideModal(id) {
    document.getElementById(id).classList.remove('show');
}

function openEditModal(id) {
    const user = users.find(u => u.id === id);
    if (!user) return;
    document.getElementById('edit-name').value = user.name;
    document.getElementById('edit-username').value = user.username;
    document.getElementById('edit-password').value = '';
    document.getElementById('form-edit').action = '{{ url("admin/users") }}/' + id;
    document.getElementById('edit-role').value = user.role;
    showModal('modal-edit');
}

</script>
@endpush