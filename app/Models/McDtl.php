<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class McDtl extends Model
{
    use HasFactory;

    protected $table = 'tmcd';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $increment = true;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'mcid',
        'braco',
        'depo',
        'formc',
        'refno',
        'opron',
        'lotno',
        'mcsts',
        'renew',
        'pvisi',
        'fvisi',
        'price',
        'shpto',
        'add01',
        'add02',
        'add03',
        'add04',
        'delcon',
        'city',
        'phone',
        'noted',
    ];

    public function mchdr()
    {
        return $this->belongsTo(McHdr::class, 'mcid', 'mcid');
    }

    public function mpromas()
    {
        return $this->belongsTo(Mpromas::class, 'opron', 'opron');
    }
}