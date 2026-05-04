<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggaranDaerah extends Model
{
    protected $table = 'anggaran_daerah';
    protected $fillable = [
        'wilayah_id',
        'anggaran_2025',
        'anggaran_2026',
        'penyesuaian',
    ];

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class);
    }

    public function list_alokasi()
    {
        return $this->hasMany(AlokasiAnggaran::class, 'anggaran_daerah_id');
    }
}
