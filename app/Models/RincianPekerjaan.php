<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RincianPekerjaan extends Model
{
    protected $table = 'rincian_pekerjaan';
    protected $fillable = [
        'paket_pekerjaan_id',
        'nama',
        'target',
        'satuan',
    ];

    public function paket_pekerjaan()
    {
        return $this->belongsTo(PaketPekerjaan::class, 'paket_pekerjaan_id');
    }

    public function list_timeline_pekerjaan()
    {
        return $this->hasMany(TimelinePekerjaan::class, 'rincian_pekerjaan_id');
    }

    public function list_realisasi_pekerjaan()
    {
        return $this->hasMany(RealisasiPekerjaan::class);
    }
}
