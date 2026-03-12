<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OcHdr extends Model
{
    use HasFactory;

    protected $table = 'tcoreh';
    protected $primaryKey = 'ocid';
    protected $keyType = 'string';
    public $increment = false;
    public $timestamps = true;

    protected $fillable = [
        'resta',
        'ocid',
        'sordt',
        'priod',
        'depo',
        'bracoformc',
        'braco',
        'formc',
        'sorno',
        'cusno',
        'sreno',
        'wdelto',
        'nodeb',
        'cuspo',
        'topay',
        'curco',
        'crate',
        'delto',
        'dtime',
        'idprov',
        'idkab',
        'rcuto',
        'ebtyp',
        'edisp',
        'edisa',
        'gross',
        'sqper',
        'sqtbr',
        'sqtsr',
        'sqper2',
        'sqtbr2',
        'sqtsr2',
        'dpper',
        'dpamt',
        'dpist',
        'prctr',
        'sts01',
        'sts02',
        'sts03',
        'lauid',
        'noteh',
        'cancd',
        'cancp',
        'reason',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public function ocdtls()
    {
        return $this->hasMany(OcDtl::class, 'ocid', 'ocid');
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