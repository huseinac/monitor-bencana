<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusAnggaran extends Model
{
    use SoftDeletes;
    protected $table = 'status_anggaran';
    protected $fillable = [
        'nama',
        'created_at',
        'updated_at'
    ];
}
