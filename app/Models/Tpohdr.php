<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tpohdr extends Model
{
    use HasFactory;

    protected $table = 'pohdr_tbl';
    protected $primaryKey = 'pono';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'pono',
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
        'supno',
    ];

    public function vendor()
    {
        return $this->belongsTo(Mvendor::class, 'supno', 'supno');
    }
}

