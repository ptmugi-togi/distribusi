<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BpbDtl extends Model
{
    use HasFactory;

    protected $table = 'tsreqd';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $increment = true;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'bpbid',
        'braco',
        'formc',
        'reqno',
        'opron',
        'rqqty',
        'rcqty',
        'eariv',
        'noted',
        'aloka'
    ];

    public function bpbhdr()
    {
        return $this->belongsTo(BbmHdr::class, 'bpbid', 'bpbid');
    }

    public function mpromas()
    {
        return $this->belongsTo(Mpromas::class, 'opron', 'opron');
    }
}