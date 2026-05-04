<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Indikator extends Model
{
    use SoftDeletes;
    protected $table = 'indikator';
    protected $fillable = [
        'kode',
        'parent_kode',
        'nama',
        'keterangan',
        'icon',
        'icon2',
        'icon3',
        'satuan'
    ];

    public function list_pelaksana()
    {
        return $this->hasMany(PelaksanaIndikator::class, 'indikator_id')->with('pelaksana');
    }

    public function parent()
    {
        return $this->belongsTo(Indikator::class, 'parent_kode', 'kode');
    }

    public function children()
    {
        return $this->hasMany(Indikator::class, 'parent_kode', 'kode')->with('list_pelaksana');
    }
}
