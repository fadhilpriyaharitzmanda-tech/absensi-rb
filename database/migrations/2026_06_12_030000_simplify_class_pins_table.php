<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_pins', function (Blueprint $table) {
            $table->dropColumn(['recovery_pin', 'recovery_pin_plain']);
            $table->integer('pin_version')->default(1)->after('pin_updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('class_pins', function (Blueprint $table) {
            $table->string('recovery_pin', 255)->nullable()->after('pin_plain');
            $table->string('recovery_pin_plain', 6)->nullable()->after('recovery_pin');
            $table->dropColumn('pin_version');
        });
    }
};
