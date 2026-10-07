<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('class_pins', 'pin_updated_at')) {
            Schema::table('class_pins', function (Blueprint $table) {
                $table->timestamp('pin_updated_at')->nullable()->after('pin');
            });
        }
    }

    public function down(): void
    {
        Schema::table('class_pins', function (Blueprint $table) {
            $table->dropColumn('pin_updated_at');
        });
    }
};
