<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoDtl extends Model
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
        'warco',
        'locco',
        'reffc',
        'refno',
        'noted',
    ];

    public function dohdr()
    {
        return $this->belongsTo(DoHdr::class, 'bbkid', 'bbkid');
    }

    public function mpromas()
    {
        return $this->belongsTo(Mpromas::class, 'opron', 'opron');
    }
}