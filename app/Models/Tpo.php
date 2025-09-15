<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tpo extends Model
{
    use HasFactory;

    protected $table = 'pohdr_tbl';
    protected $primaryKey = 'pono';
    public $timestamps = false;
    protected $fillable = [
        'formc',
        'podat',
        'potype',
        'topay',
        'tdesc',
        'curco',
        'shvia',
        'sconp',
        'delco',
        'delnm',
        'dconp',
        'diper',
        'vatax',
        'pph',
        'stamp',
        'noteh',
    ];
}
