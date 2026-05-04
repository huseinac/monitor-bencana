<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penyedia extends Model
{
    protected $table = 'penyedia';
    protected $fillable = [
        'nama',
        'nib',
        'npwp',
        'kontak_person',
        'email',
        'notelp',
        'alamat',
    ];
}
