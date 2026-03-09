<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OcSbDtl extends Model
{
    use HasFactory;

    protected $table = 'tprojb';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $increment = true;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'ocsbid',
        'braco',
        'formc',
        'sorno',
        'delto',
        'opron',
        'prona',
        'stdqu',
        'putama',
        'qtyor',
        'qtydo',
        'qtyin',
        'tobed',
        'plist',
        'price',
        'odisp',
        'odisa',
        'teknik',
        'insby',
        'insdt',
        'rqeta',
        'whetd',
        'srcog',
        'noted',
        'cancp',
        'cancd',
    ];

    public function ocsbhdr()
    {
        return $this->belongsTo(OcSbHdr::class, 'ocsbid', 'ocsbid');
    }

    public function mpromas()
    {
        return $this->belongsTo(Mpromas::class, 'opron', 'opron');
    }

    public function getSiteAttribute()
    {
        if (!$this->ocsbhdr) {
            return null;
        }

        return MstMas::where('braco', $this->ocsbhdr->braco)
            ->where('cusno', $this->ocsbhdr->cusno)
            ->where('shpto', $this->delto)
            ->first();
    }

    public function mbranch()
    {
        return $this->belongsTo(Mbranch::class, 'insby', 'braco');
    }
}