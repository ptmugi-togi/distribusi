<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WoffDtl extends Model
{
    use HasFactory;

    protected $table = 'twoffd';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $increment = true;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'woffid',
        'bracoformc',
        'braco',
        'formc',
        'vcrno',
        'invfc',
        'invrn',
        'cusno',
        'trval',
        'curco',
        'irate',
        'noted'
    ];

    public function woffhdr()
    {
        return $this->hasMany(WoffHdr::class, 'woffid', 'woffid');
    }

    public function mcusmas()
    {
        return $this->belongsTo(Mcusmas::class, 'cusno', 'cusno');
    }
}