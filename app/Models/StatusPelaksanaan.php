<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusPelaksanaan extends Model
{
    use SoftDeletes;
    protected $table = 'status_pelaksanaan';
    protected $fillable = [
        'nama',
        'created_at',
        'updated_at'
    ];
}
