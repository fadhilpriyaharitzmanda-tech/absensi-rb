<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'nis',
        'absent_number',
        'photo'
    ];

    protected static function booted()
    {
        static::creating(function ($student) {
            if (empty($student->slug)) {
                $slug = Str::slug($student->name);
                $original = $slug;
                $counter = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $original . '-' . $counter++;
                }
                $student->slug = $slug;
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function getAttendancePercentage($month = null, $year = null): float
    {
        $month = $month ?? now()->month;
        $year  = $year  ?? now()->year;

        $total = $this->attendances()
            ->whereHas(
                'session',
                fn($q) =>
                $q->whereMonth('date', $month)->whereYear('date', $year)
            )->count();

        if ($total === 0) return 0;

        $hadir = $this->attendances()
            ->whereHas(
                'session',
                fn($q) =>
                $q->whereMonth('date', $month)->whereYear('date', $year)
            )->where('status', 'hadir')->count();

        return round(($hadir / $total) * 100, 1);
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo && file_exists(storage_path('app/public/' . $this->photo))) {
            return asset('storage/' . $this->photo);
        }

        // Generate avatar otomatis dari inisial nama
        $initials = collect(explode(' ', $this->name))
            ->take(2)
            ->map(fn($word) => strtoupper($word[0]))
            ->implode('');

        return 'https://ui-avatars.com/api/?name=' . urlencode($initials)
            . '&background=e0e7ff&color=3730a3&bold=true&font-size=0.4&size=128';
    }
}
