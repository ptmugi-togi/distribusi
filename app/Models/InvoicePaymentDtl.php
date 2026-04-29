<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePaymentDtl extends Model
{
    use HasFactory;

    protected $table = 'tpayind';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $increment = true;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'invpid',
        'braco',
        'formc',
        'vcrno',
        'iorno',
        'cusno',
        'invfc',
        'invrn',
        'pcval',
        'pcwo',
        'payva',
        'noted'
    ];

    public function invoicepaymenthdr()
    {
        return $this->hasMany(InvoicePaymentHdr::class, 'invpid', 'invpid');
    }

    public function mpromas()
    {
        return $this->belongsTo(Mpromas::class, 'opron', 'opron');
    }
}