<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlokasiAnggaran extends Model
{
    protected $table = 'alokasi_anggaran';
    protected $fillable = [
        'anggaran_daerah_id',
        'keterangan',
        'nominal',
        'nama_realisasi'
    ];

    public function anggaran_daerah()
    {
        return $this->belongsTo(AnggaranDaerah::class);
    }
}
