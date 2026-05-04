<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SektorTerdampak extends Model
{
    const LIST_KONDISI = ['RR' => 'Rusak Ringan', 'RS' => 'Rusak Sedang', 'RB' => 'Rusak Berat'];
    protected $table = 'sektor_terdampak';
    protected $fillable = [
        'wilayah_id',
        'indikator_id',
        'kondisi_awal',
        'keterangan',
        'batas_waktu',
        'foto_sebelum',
        'foto_sesudah',
        'latitude',
        'longitude',
        'kondisi',
        'status',
        'nama_lokasi',
        'alamat'
    ];

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class);
    }

    public function indikator()
    {
        return $this->belongsTo(Indikator::class);
    }

    public function list_perbaikan()
    {
        return $this->hasMany(Perbaikan::class, 'sektor_terdampak_id');
    }

    public function getKondisiCaptionAttribute()
    {
        return self::LIST_KONDISI[$this->kondisi];
    }
}
