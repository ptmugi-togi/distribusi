<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DpInvRelHdr extends Model
{
    use HasFactory;

    protected $table = 'tinmas';
    protected $primaryKey = 'invid';
    protected $keyType = 'string';
    public $increment = false;
    public $timestamps = true;

    protected $fillable = [
        'invid',
        'bracoformc',
        'braco',
        'formc',
        'invno',
        'invdt',
        'priod',
        'duedt',
        'delto',
        'warco',
        'dorfc',
        'donom',
        'sorfc',
        'sorno',
        'topli',
        'fpnum',
        'fpdat',
        'cusno',
        'sreno',
        'cuspo',
        'quote',
        'topay',
        'ortyp',
        'vatax',
        'curco',
        'crate',
        'instf',
        'gramt',
        'dpper',
        'odisa',
        'ntamt',
        'dpamt',
        'txamt',
        'stamp',
        'blamt',
        'caval',
        'cramt',
        'recwo',
        'lpdat',
        'edisp',
        'edisa',
        'itext',
        'prctr',
        'precwo',
        'pcaval',
        'pcramt',
        'parval',
        'pmode',
        'sts01',
        'invtp',
        'wofcd',
        'wonum',
        'intxt',
        'divco',
        'mcfcd',
        'mcno',
        'phase',
        'optp1',
        'optp2',
        'optp3',
        'optp4',
        'dndat',
        'noteh',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    public function dpinvreldtls()
    {
        return $this->hasMany(DpInvRelDtl::class, 'invid', 'invid');
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

    public function msreno_split()
    {
        return $this->belongsTo(Msreno::class, 'sqtsr', 'sreno');
    }

    public function mtaxes()
    {
        return $this->belongsTo(Mtaxes::class, 'braco', 'braco');
    }

    public function mstmas()
    {
        return $this->belongsTo(MstMas::class, 'cusno', 'cusno');
    }

    public function mstmas_print()
    {
        return $this->hasOne(Mstmas::class, 'cusno', 'cusno')
            ->where('shpto', $this->delto);
    }

    public function mdepo()
    {
        return $this->belongsTo(Mdepo::class, 'depo', 'depo');
    }
}