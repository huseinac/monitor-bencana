<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPaketPekerjaan extends Model
{
    protected $table = 'kategori_paket_pekerjaan';
    protected $fillable = [
        'nama',
        'icon',
    ];
}
