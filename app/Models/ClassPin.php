<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassPin extends Model
{
    protected $fillable = ['kode_kelas', 'pin', 'pin_updated_at', 'pin_version'];

    protected $hidden = ['pin'];
}
