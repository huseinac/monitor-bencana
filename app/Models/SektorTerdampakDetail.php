<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SektorTerdampakDetail extends Model
{
    protected $table = 'sektor_terdampak_detail';
    protected $fillable = [
        'sektor_terdampak_id',
        'keterangan',
        'latitude',
        'longitude',
        'foto',
    ];

    public function sektor_terdampak()
    {
        return $this->belongsTo(SektorTerdampak::class);
    }
}
