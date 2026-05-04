<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorImport extends Model
{
    protected $table = 'error_imports';
    public $timestamps = false;
    protected $fillable = [
        'kecamatan',
        'penyebab',
        'data'
    ];
}
