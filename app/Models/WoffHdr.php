<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WoffHdr extends Model
{
    use HasFactory;

    protected $table = 'twoffh';
    protected $primaryKey = 'woffid';
    protected $keyType = 'string';
    public $increment = false;
    public $timestamps = true;

    protected $fillable = [
        'resta',
        'woffid',
        'bracoformc',
        'braco',
        'formc',
        'vcrno',
        'tradt',
        'priod',
        'sts01',
        'refno',
        'noteh',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    public function woffdtls()
    {
        return $this->hasMany(WoffDtl::class, 'woffid', 'woffid');
    }

    public function mbranch()
    {
        return $this->belongsTo(Mbranch::class, 'braco', 'braco');
    }

    public function mformcode()
    {
        return $this->belongsTo(Mformcode::class, 'bracoformc', 'bracoformc');
    }

    public function mcusmas()
    {
        return $this->belongsTo(Mcusmas::class, 'cusno', 'cusno');
    }

    public function mtaxes()
    {
        return $this->belongsTo(Mtaxes::class, 'braco', 'braco');
    }
}