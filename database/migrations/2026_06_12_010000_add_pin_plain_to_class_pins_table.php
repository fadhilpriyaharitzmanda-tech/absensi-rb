<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_pins', function (Blueprint $table) {
            $table->string('pin_plain', 6)->nullable()->after('pin');
        });

        DB::table('class_pins')->where('kode_kelas', '11-RPL-B')->update([
            'pin_plain' => '123456',
        ]);
    }

    public function down(): void
    {
        Schema::table('class_pins', function (Blueprint $table) {
            $table->dropColumn('pin_plain');
        });
    }
};
