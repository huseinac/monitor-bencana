<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimelinePekerjaan extends Model
{
    protected $table = 'timeline_pekerjaan';
    protected $fillable = [
        'rincian_pekerjaan_id',
        'tanggal_awal',
        'tanggal_akhir',
        'target'
    ];

    public function rincian_pekerjaan()
    {
        return $this->belongsTo(RincianPekerjaan::class, 'rincian_pekerjaan_id');
    }
}
