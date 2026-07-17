<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mpromas extends Model
{
    public $table = "mpromas";
    use HasFactory;
    public $timestamps = false;
    protected $keyType = 'integer';
    protected $primaryKey = 'mproma';
    public $increment = true;
    protected $fillable = 
    [
        'status',
        'opron',
        'prona',
        'iname',
        'nama_supplier',
        'stdqu',
        'itype_id',
        'brand',
        'pgrup',
        'sgrup_id',
        'ssgrup_id',
        'lssgrup',
        'weigh',
        'meast',
        'measl',
        'measp',
        'volum',
        'abccl',
        'capac',
        'platf',
        'mstok',
        'spnum',
        'garan',
        'acinv',
        'achpp',
        'acals',
        'acdis',
        'pbilp',
        'ijtype',
        'id_cls'
    ];

    public function tpodtls()
    {
        return $this->hasMany(TpoDtl::class, 'opron', 'opron');
    }

    public function mitype()
    {
        return $this->belongsTo(Mitype::class, 'itype_id', 'itype_id');
    }

    public function mpgrup()
    {
        return $this->belongsTo(Mpgrup::class, 'pgrup', 'pgrup');
    }

    public function msgrup()
    {
        return $this->belongsTo(Msgrup_tbl::class, 'sgrup_id', 'sgrup_id');
    }

    public function mssgrup()
    {
        return $this->belongsTo(Mssgrup_tbl::class, 'ssgrup_id', 'ssgrup_id');
    }

    public function mlssgrup()
    {
        return $this->belongsTo(Mssgrup_tbl::class, 'lssgrup', 'ssgrup_id');
    }
}
