<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function attendanceSessions()
    {
        return $this->hasMany(AttendanceSession::class, 'created_by');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isPengurusKelas(): bool
    {
        return $this->role === 'pengurus_kelas';
    }

    public function isAdminOrGuru(): bool
    {
        return in_array($this->role, ['admin', 'guru']);
    }

    public function canInputPresensi(): bool
    {
        return in_array($this->role, ['admin', 'pengurus_kelas']);
    }

    public function canDownloadLaporan(): bool
    {
        return in_array($this->role, ['admin', 'guru']);
    }
}
