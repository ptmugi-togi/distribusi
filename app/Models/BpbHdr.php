<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BpbHdr extends Model
{
    use HasFactory;

    protected $table = 'tsreqh';
    protected $primaryKey = 'bpbid';
    protected $keyType = 'string';
    public $increment = false;
    public $timestamps = true;

    protected $fillable = [
        'bpbid',
        'braco',
        'formc',
        'reqno',
        'reqdt',
        'reqto',
        'reqtn',
        'rqfor',
        'delto',
        'delco',
        'sorfc',
        'sorno',
        'noteh',
        'prctr',
        'contp',
        'supno',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    public function bpbdtls()
    {
        return $this->hasMany(BpbDtl::class, 'bpbid', 'bpbid');
    }

    public function mbranch()
    {
        return $this->belongsTo(Mbranch::class, 'reqto', 'braco');
    }

    public function mwarco()
    {
        return $this->belongsTo(Mwarco::class, 'delco', 'warco');
    }

    public function mformcode()
    {
        return $this->belongsTo(Mformcode::class, 'bracoformc', 'bracoformc');
    }

    public function mpromas()
    {
        return $this->belongsTo(Mpromas::class, 'opron', 'opron');
    }
}