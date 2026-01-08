<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WoDtl extends Model
{
    use HasFactory;

    protected $table = 'tworkd';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $increment = true;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'woid',
        'braco',
        'formc',
        'wonum',
        'outpr',
        'outqt',
        'stdqu',
        'acqty',
        'noted',
    ];

    public function wohdr()
    {
        return $this->belongsTo(BbmHdr::class, 'woid', 'woid');
    }

    public function mpromas()
    {
        return $this->belongsTo(Mpromas::class, 'outpr', 'opron');
    }
}