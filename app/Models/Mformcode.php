<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mformcode extends Model
{
    protected $table = 'mformcode_tbl';
    protected $primaryKey = 'formc';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'formc',
        'desc',
        'pos1',
        'name1',
        'pos2',
        'name2',
        'pos3',
        'name3',
        'pos4',
        'name4',
        'docd', 
    ];
}
