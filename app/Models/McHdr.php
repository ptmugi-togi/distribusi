<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class McHdr extends Model
{
    use HasFactory;

    protected $table = 'tmch';
    protected $primaryKey = 'mcid';
    protected $keyType = 'string';
    public $increment = false;
    public $timestamps = true;

    protected $fillable = [
        'resta',
        'mcid',
        'braco',
        'depo',
        'formc',
        'bracoformc',
        'refno',
        'mcdat',
        'priod',
        'cusno',
        'quote',
        'mcnom',
        'gramt',
        'odisa',
        'odisp',
        'ntamt',
        'vatax',
        'txamt',
        'blamt',
        'curco',
        'noteh',
        'gmcfr',
        'gmcto',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public function mcdtls()
    {
        return $this->hasMany(McDtl::class, 'mcid', 'mcid');
    }

    public function mcphase()
    {
        return $this->hasMany(McPayment::class, 'mcid', 'mcid');
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

    public function mstmas()
    {
        return $this->belongsTo(MstMas::class, 'delto', 'shpto')
            ->where('cusno', $this->cusno);
    }
}