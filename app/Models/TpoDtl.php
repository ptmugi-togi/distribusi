<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TpoDtl extends Model
{
    use HasFactory;

    protected $table = 'podtl_tbl';
    protected $primaryKey = 'idpo';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $fillable = [
        'idpo',
        'pono',
        'formc',
        'supno',
        'opron',
        'prona',
        'poqty',
        'stdqu',
        'weigh',
        'berat',
        'price',
        'odisp',
        'edeld',
        'earrd',
        'hsn',
        'bm',
        'bmt',
        'pphd',
        'noted',
    ];

    public function tpohdr()
    {
        return $this->belongsTo(TpoHdr::class, 'pono', 'pono');
    }

    public function mpromas()
    {
        return $this->belongsTo(Mpromas::class, 'opron', 'opron');
    }
}



