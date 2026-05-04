<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasalahKritis extends Model
{
    protected $table = 'masalah_kritis';
    protected $fillable = [
        'indikator_id',
        'pelaksana_id',
        'wilayah_id',
        'keterangan',
        'jumlah',
        'satuan',
        'foto',
        'foto_sesudah',
        'latitude',
        'longitude',
    ];

    public function indikator()
    {
        return $this->belongsTo(Indikator::class);
    }

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class);
    }

    public function pelaksana()
    {
        return $this->belongsTo(Pelaksana::class);
    }
}
