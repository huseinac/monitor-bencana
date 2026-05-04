<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PelaksanaIndikator extends Model
{
    protected $table = 'pelaksana_indikator';
    protected $fillable = [
        'indikator_id',
        'pelaksana_id',
    ];

    public function indikator()
    {
        return $this->belongsTo(Indikator::class, 'indikator_id');
    }

    public function pelaksana()
    {
        return $this->belongsTo(Pelaksana::class, 'pelaksana_id');
    }
}
