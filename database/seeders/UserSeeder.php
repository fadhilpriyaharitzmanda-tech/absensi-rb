<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun guru kelas
        User::create([
            'name'     => 'Guru Kelas',
            'username' => 'pengurus',
            'password' => Hash::make('pengurus123'),
            'role'     => 'guru',
        ]);
    }       
}
