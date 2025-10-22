<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mcurco extends Model
{
    use HasFactory;

    protected $table = 'mcurco_tbl';

    protected $primaryKey = 'curco';
    public $incrementing = false;
    protected $keyType = 'string';

    // Field yang boleh diisi
    protected $fillable = [
        'curco',
        'desc_curco',
        'crate', 
    ];

    public $timestamps = false;
}
