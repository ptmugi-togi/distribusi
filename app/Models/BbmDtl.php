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

}