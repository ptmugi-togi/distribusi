<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApPaymentDtl extends Model
{
    use HasFactory;

    protected $table = 'tpayapd';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $increment = true;
    public $timestamps = false;

    protected $fillable = [
        'no',
        'apid',
        'braco',
        'formc',
        'vcrno',
        'invno',
        'curco',
        'invrate',
        'payrate',
        'payval',
        'adjust',
        'totalidr',
        'noted'
    ];

    public function appaymenthdr()
    {
        return $this->belongsTo(ApPaymentHdr::class, 'apid', 'apid');
    }
}