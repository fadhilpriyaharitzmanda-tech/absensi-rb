<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('role')->orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:guru,admin,pengurus_kelas',
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'password' => 'nullable|string|min:6',
            'role'     => 'required|in:guru,admin,pengurus_kelas',
        ]);

        // Cegah admin mengunci diri sendiri atau menghapus admin terakhir
        if ($user->role === 'admin' && $data['role'] !== 'admin') {
            if ($user->id === auth()->id()) {
                return back()->withErrors(['role' => 'Anda tidak dapat mencabut hak akses Admin dari akun Anda sendiri.']);
            }
            if (User::where('role', 'admin')->where('id', '!=', $user->id)->count() === 0) {
                return back()->withErrors(['role' => 'Sistem harus memiliki setidaknya satu admin aktif.']);
            }
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->where('id', '!=', $user->id)->count() === 0) {
            return redirect()->route('admin.users.index')->with('error', 'Tidak dapat menghapus satu-satunya akun admin yang tersisa.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil dihapus.');
    }
}
