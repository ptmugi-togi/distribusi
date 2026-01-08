<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WoHdr extends Model
{
    use HasFactory;

    protected $table = 'tworkh';
    protected $primaryKey = 'woid';
    protected $keyType = 'string';
    public $increment = false;
    public $timestamps = true;

    protected $fillable = [
        'woid',
        'braco',
        'formc',
        'wonum',
        'wodat',
        'priod',
        'ppose',
        'reqbr',
        'reffc',
        'refno',
        'sorno',
        'reqby',
        'reqdt',
        'cusna',
        'costc',
        'fdate',
        'noteh',
        'ladup',
        'lauid',
        'wocdt',
        'wocpr',
        'prctr',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public function wodtls()
    {
        return $this->hasMany(WoDtl::class, 'woid', 'woid');
    }

    public function mbranch()
    {
        return $this->belongsTo(Mbranch::class, 'braco', 'braco');
    }

    public function mformcode()
    {
        return $this->belongsTo(Mformcode::class, 'formc', 'formc');
    }
}