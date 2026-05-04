<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranPekerjaan extends Model
{
    protected $table = 'pembayaran_pekerjaan';
    protected $fillable = [
        'paket_pekerjaan_id',
        'tanggal',
        'nominal'
    ];

    public function paket_pekerjaan()
    {
        return $this->belongsTo(PaketPekerjaan::class);
    }
}
