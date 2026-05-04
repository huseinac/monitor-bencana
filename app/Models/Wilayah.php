<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $table = 'wilayah';
    protected $fillable = [
        'kode',
        'parent_kode',
        'nama',
        'polygon',
        'latitude',
        'longitude',
        'kondisi'
    ];

    public function parent()
    {
        return $this->belongsTo(Wilayah::class, 'parent_kode', 'kode');
    }

    public function children()
    {
        return $this->hasMany(Wilayah::class, 'parent_kode', 'kode');
    }
}
