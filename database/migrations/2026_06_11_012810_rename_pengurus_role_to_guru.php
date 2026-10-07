<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah 'guru' dulu (pengurus masih ada)
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('pengurus', 'siswa', 'admin', 'guru') NOT NULL DEFAULT 'siswa'");
        }

        // Update data
        DB::table('users')->where('role', 'pengurus')->update(['role' => 'guru']);

        // Hapus 'pengurus' dari enum
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('guru', 'siswa', 'admin') NOT NULL DEFAULT 'siswa'");
        }
    }

    public function down(): void
    {
        // Tambah 'pengurus' dulu
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('guru', 'siswa', 'admin', 'pengurus') NOT NULL DEFAULT 'siswa'");
        }

        // Kembalikan data
        DB::table('users')->where('role', 'guru')->update(['role' => 'pengurus']);

        // Hapus 'guru' dari enum
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('pengurus', 'siswa', 'admin') NOT NULL DEFAULT 'siswa'");
        }
    }
};
