<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Drop plaintext pin column if it exists for security
        if (Schema::hasColumn('class_pins', 'pin_plain')) {
            Schema::table('class_pins', function (Blueprint $table) {
                $table->dropColumn('pin_plain');
            });
        }

        // 2. Add performance index on date for attendance_sessions
        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->index('date');
        });

        // 3. Add composite performance indexes on attendances
        Schema::table('attendances', function (Blueprint $table) {
            $table->index(['session_id', 'status']);
            $table->index(['student_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex(['session_id', 'status']);
            $table->dropIndex(['student_id', 'status']);
        });

        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->dropIndex(['date']);
        });

        if (!Schema::hasColumn('class_pins', 'pin_plain')) {
            Schema::table('class_pins', function (Blueprint $table) {
                $table->string('pin_plain', 6)->nullable()->after('pin');
            });
        }
    }
};
