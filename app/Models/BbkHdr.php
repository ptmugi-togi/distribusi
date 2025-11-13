<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BbkHdr extends Model
{
    use HasFactory;

    protected $table = 'tsisnh';
    protected $primaryKey = 'bbkid';
    protected $keyType = 'string';
    public $increment = false;
    public $timestamps = true;

    protected $fillable = [
        'bbkid',
        'braco',
        'warco',
        'formc',
        'trano',
        'tradt',
        'priod',
        'rfc01',
        'ref01',
        'rfc02',
        'ref02',
        'rfc03',
        'ref03',
        'div04',
        'rfc04',
        'ref04',
        'isutn',
        'costc',
        'exped',
        'prctr',
        'isua1',
        'isua2',
        'isua3',
        'noteh',
        'supno',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'user_id'
    ];

    public function bbkdtls()
    {
        return $this->hasMany(BbkDtl::class, 'bbkid', 'bbkid');
    }

    public function mformcode()
    {
        return $this->belongsTo(Mformcode::class, 'formc', 'formc');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo(Mvendor::class, 'supno', 'supno');
    }

    public function tsupih()
    {
        return $this->belongsTo(InvoiceHdr::class, 'refno', 'rinum');
    }

    public function tbolh()
    {
        return $this->belongsTo(Tbolh::class, 'blnum', 'blnum');
    }
}