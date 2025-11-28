<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaDtl extends Model
{
    use HasFactory;

    protected $table = 'toutg';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $increment = true;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'bbkid',
        'formc',
        'trano',
        'opron',
        'qunit',
        'trqty',
        'lotno',
        'locco',
        'reffc',
        'refno',
        'noted',
    ];

    public function tahdr()
    {
        return $this->belongsTo(BbmHdr::class, 'bbkid', 'bbkid');
    }

    public function mpromas()
    {
        return $this->belongsTo(Mpromas::class, 'opron', 'opron');
    }
}