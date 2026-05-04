<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelaksana extends Model
{
    use SoftDeletes;
    protected $table = 'pelaksana';
    protected $fillable = [
        'nama',
        'singkatan',
        'keterangan',
    ];
}
