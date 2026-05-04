<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    protected $table = 'perbaikan';
    protected $fillable = [
        'sektor_terdampak_id',
        'tanggal',
        'pelapor',
        'foto',
        'keterangan',
    ];

    public function sektor_terdampak()
    {
        return $this->belongsTo(SektorTerdampak::class);
    }
}
