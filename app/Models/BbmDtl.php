<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BbmDtl extends Model
{
    use HasFactory;

    protected $table = 'tstord';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $increment = true;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'bbmid',
        'opron',
        'trqty',
        'qunit',
        'lotno',
        'invno',
        'porfc',
        'pono',
        'noted'
    ];

    public function bbmhdr()
    {
        return $this->belongsTo(BbmHdr::class, 'bbmid', 'bbmid');
    }

    public function mpromas()
    {
        return $this->belongsTo(Mpromas::class, 'opron', 'opron');
    }

    public function tsupid()
    {
        return $this->belongsTo(InvoiceDtl::class, 'invno', 'invno');
    }

    public function podtl()
    {
        return $this->belongsTo(TpoDtl::class, 'pono', 'pono');
    }

    public function getLoccoDescrAttribute()
    {
        if (! $this->locco || ! $this->bbmhdr?->warco) {
            return null;
        }

        return \App\Models\Mlocco::where('warco', $this->bbmhdr->warco)
            ->where('locco', $this->locco)
            ->value('descr');
    }
}