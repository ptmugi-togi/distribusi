<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApPaymentHdr extends Model
{
    use HasFactory;

    protected $table = 'tpayaph';
    protected $primaryKey = 'apid';
    protected $keyType = 'string';
    public $increment = false;
    public $timestamps = true;

    protected $fillable = [
        'apid',
        'resta',
        'braco',
        'formc',
        'bracoformc',
        'vcrno',
        'pdate',
        'priod',
        'supno',
        'glacc',
        'bankname',
        'tpayment',
        'tpayment_detail',
        'curco',
        'payrate',
        'noteh',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];

    public function details()
    {
        return $this->hasMany(ApPaymentDtl::class, 'apid', 'apid')
            ->orderBy('no', 'asc');
    }

    public function supplier()
    {
        return $this->belongsTo(Mvendor::class, 'supno', 'supno');
    }
}