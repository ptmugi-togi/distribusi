<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePaymentHdr extends Model
{
    use HasFactory;

    protected $table = 'tpayinh';
    protected $primaryKey = 'invpid';
    protected $keyType = 'string';
    public $increment = false;
    public $timestamps = true;

    protected $fillable = [
        'resta',
        'invpid',
        'braco',
        'formc',
        'vcrno',
        'iorno',
        'pdate',
        'priod',
        'cusno',
        'tpaye',
        'curco',
        'prate',
        'noteh',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    public function invoicepaymentdtls()
    {
        return $this->hasMany(InvoicePaymentDtl::class, 'invpid', 'invpid');
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