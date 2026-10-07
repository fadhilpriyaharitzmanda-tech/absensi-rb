<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_pins', function (Blueprint $table) {
            $table->string('recovery_pin', 255)->nullable()->after('pin_plain');
            $table->string('recovery_pin_plain', 6)->nullable()->after('recovery_pin');
        });

        DB::table('class_pins')->where('kode_kelas', '11-RPL-B')->update([
            'recovery_pin' => Hash::make('000000'),
            'recovery_pin_plain' => '000000',
        ]);
    }

    public function down(): void
    {
        Schema::table('class_pins', function (Blueprint $table) {
            $table->dropColumn(['recovery_pin', 'recovery_pin_plain']);
        });
    }
};
