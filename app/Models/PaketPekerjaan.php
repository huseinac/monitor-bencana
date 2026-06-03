<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketPekerjaan extends Model
{
    protected $table = 'paket_pekerjaan';
    protected $fillable = [
        'wilayah_id',
        'indikator_id',
        'pelaksana_id',
        'nama',
        'nominal',
        'keterangan',
        'latitude',
        'longitude',
        'kategori_paket_pekerjaan_id',
        'penyedia_id',
        'tahun_perencanaan',
        'tahun_usulan',
        'tahun_anggaran',
        'tahun_pelaksanaan_pekerjaan',
        'nama_program',
        'nama_kegiatan',
        'nama_sub_kegiatan',
        'nama_rekening',
        'pagu_dana',
        'no_kontrak',
        'nama_paket',
        'jenis_pengadaan',
        'model_pengadaan',
        'tanggal_kontrak',
        'tanggal_selesai',
        'nilai_pagu',
        'nilai_kontrak',
        'status_anggaran_id',
        'status_pelaksanaan_id'
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

    public function penyedia()
    {
        return $this->belongsTo(Penyedia::class);
    }

    public function kategori_paket_pekerjaan()
    {
        return $this->belongsTo(KategoriPaketPekerjaan::class);
    }

    public function list_rincian_pekerjaan()
    {
        return $this->hasMany(RincianPekerjaan::class);
    }

    public function list_pembayaran()
    {
        return $this->hasMany(PembayaranPekerjaan::class, 'paket_pekerjaan_id');
    }
}
