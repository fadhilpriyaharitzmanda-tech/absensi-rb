<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_pins', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kelas')->unique();
            $table->string('pin');
            $table->timestamp('pin_updated_at')->nullable();
            $table->timestamps();
        });

        DB::table('class_pins')->insert([
            'kode_kelas' => '11-RPL-B',
            'pin' => Hash::make('123456'),
            'pin_updated_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('class_pins');
    }
};
