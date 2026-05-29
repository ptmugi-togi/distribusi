<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DnHdr extends Model
{
    use HasFactory;

    protected $table = 'tdnh';
    protected $primaryKey = 'dnid';
    protected $keyType = 'string';
    public $increment = false;
    public $timestamps = true;

    protected $fillable = [
        'resta',
        'dnid',
        'bracoformc',
        'braco',
        'depo',
        'formc',
        'dnnum',
        'dndat',
        'priod',
        'cusno',
        'delto',
        'quote',
        'cuspo',
        'topay',
        'vatax',
        'curco',
        'crate',
        'gramt',
        'odisa',
        'ntamt',
        'dpamt',
        'txamt',
        'blamt',
        'total_service',
        'total_sparepart',
        'itext',
        'prctr',
        'sts01',
        'wofcd',
        'wonum',
        'intxt',
        'optp1',
        'optp2',
        'optp3',
        'optp4',
        'invfc',
        'invno',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];

    public function dndtls()
    {
        return $this->hasMany(DnDtl::class, 'dnid', 'dnid');
    }

    public function dndtbs()
    {
        return $this->hasMany(DnDtlService::class, 'dnid', 'dnid');
    }

    public function dndtcs()
    {
        return $this->hasMany(DnDtlSparepart::class, 'dnid', 'dnid');
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