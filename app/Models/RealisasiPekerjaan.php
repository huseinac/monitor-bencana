<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealisasiPekerjaan extends Model
{
    protected $table = 'realisasi_pekerjaan';
    protected $fillable = [
        'rincian_pekerjaan_id',
        'tanggal',
        'realisasi'
    ];

    public function rincian_pekerjaan()
    {
        return $this->belongsTo(RincianPekerjaan::class, 'rincian_pekerjaan_id');
    }
}
