<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BbmHdr extends Model
{
    use HasFactory;

    protected $table = 'tstorh';
    protected $primaryKey = 'bbmid';
    protected $keyType = 'string';
    public $increment = false;
    public $timestamps = true;

    protected $fillable = [
        'bbmid',
        'braco',
        'warco',
        'formc',
        'trano',
        'priod',
        'tradt',
        'refwh',
        'reffc',
        'refno',
        'supno',
        'blnum',
        'vesel',
        'noteh',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'user_id',
    ];

    public function bbmdtl()
    {
        return $this->hasMany(BbmDtl::class, 'bbmid', 'bbmid');
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