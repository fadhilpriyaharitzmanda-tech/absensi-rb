<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('name');
        });

        // Generate slug untuk data existing
        $students = DB::table('students')->get();
        foreach ($students as $student) {
            $slug = Str::slug($student->name);
            $original = $slug;
            $counter = 1;
            while (DB::table('students')->where('slug', $slug)->exists()) {
                $slug = $original . '-' . $counter++;
            }
            DB::table('students')->where('id', $student->id)->update(['slug' => $slug]);
        }

        Schema::table('students', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
