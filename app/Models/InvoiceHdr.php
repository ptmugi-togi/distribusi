<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoiceHdr extends Model
{
    use HasFactory;

    protected $table = 'tsupih_tbl';
    protected $primaryKey = 'invno';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;
    protected $fillable = [
        'braco',
        'supno',
        'formc',
        'invno',
        'duedt',
        'rinum',
        'blnum',
        'invdt',
        'curco',
        'tfreight',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'user_id',
    ];

    protected static function booted()
    {
        static::deleting(function ($invoiceHdr) {
            $invoiceHdr->invoiceDtl()->delete();
        });
    }


    public function details()
    {
        return $this->hasMany(InvoiceDtl::class, 'invno', 'invno');
    }

    public function vendor()
    {
        return $this->belongsTo(Mvendor::class, 'supno', 'supno');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function formcode()
    {
        return $this->belongsTo(Mformcode::class, 'formc', 'formc');
    }

    public function branches()
    {
        return $this->belongsTo(Mbranch::class, 'braco', 'braco');
    }

    public function currency()
    {
        return $this->belongsTo(Mcurco::class, 'curco', 'curco');
    }
}