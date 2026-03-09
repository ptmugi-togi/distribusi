<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OcSbHdr extends Model
{
    use HasFactory;

    protected $table = 'tproja';
    protected $primaryKey = 'ocsbid';
    protected $keyType = 'string';
    public $increment = false;
    public $timestamps = true;

    protected $fillable = [
        'resta',
        'ocsbid',
        'sordt',
        'priod',
        'depo',
        'bracoformc',
        'braco',
        'formc',
        'sorno',
        'cusno',
        'pcuto',
        'sreno',
        'wdelto',
        'nodeb',
        'cuspo',
        'topay',
        'curco',
        'crate',
        'gross',
        'dtime',
        'idprov',
        'idkab',
        'rcuto',
        'ebtyp',
        'edisa',
        'odisa',
        'insfe',
        'vatax',
        'vtamt',
        'billv',
        'prctr',
        'sts01',
        'sts02',
        'sts03',
        'noteh',
        'cancd',
        'cancp',
        'reason',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public function ocsbdtls()
    {
        return $this->hasMany(OcSbDtl::class, 'ocsbid', 'ocsbid');
    }

    public function invoices()
    {
        return $this->hasMany(Tprojd::class, 'ocsbid', 'ocsbid');
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

    public function msreno()
    {
        return $this->belongsTo(Msreno::class, 'sreno', 'sreno');
    }

    public function mtaxes()
    {
        return $this->belongsTo(Mtaxes::class, 'braco', 'braco');
    }

    public function mdepo()
    {
        return $this->belongsTo(Mdepo::class, 'depo', 'depo');
    }
}