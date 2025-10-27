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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

    public function tsupih()
    {
        return $this->belongsTo(InvoiceHdr::class, 'invno', 'bbmid');
    }
}