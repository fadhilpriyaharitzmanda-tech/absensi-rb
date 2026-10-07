<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'date',
        'meeting_number',
        'subject',
        'is_open',
    ];

    protected $casts = [
        'date'    => 'date',
        'is_open' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'session_id');
    }

    public function getSummaryAttribute(): array
    {
        $counts = $this->attendances()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return [
            'hadir' => $counts['hadir'] ?? 0,
            'izin'  => $counts['izin']  ?? 0,
            'sakit' => $counts['sakit'] ?? 0,
            'alpha'  => $counts['alpha']  ?? 0,
        ];
    }
}
