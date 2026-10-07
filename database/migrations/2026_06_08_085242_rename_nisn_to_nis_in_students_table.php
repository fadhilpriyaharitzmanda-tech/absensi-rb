<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('students', 'nisn')) {
            DB::statement('ALTER TABLE students CHANGE nisn nis VARCHAR(255) NOT NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('students', 'nis')) {
            DB::statement('ALTER TABLE students CHANGE nis nisn VARCHAR(255) NOT NULL');
        }
    }
};
