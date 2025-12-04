<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stobw extends Model
{
    use HasFactory;

    protected $table = 'stobw_tbl';
    protected $primaryKey = 'idbw';
    protected $keyType = 'int';
    public $increment = true;
    public $timestamps = false;

    protected $fillable = [
        'idbw',
        'braco',
        'warco',
        'opron',
        'bbqoh',
        'toqoh',
        'alqty',
    ];

    public function stobl()
    {
        return $this->hasMany(Stobl::class, 'braco', 'braco');
    }

    public function mbranch()
    {
        return $this->belongsTo(Mbranch::class, 'braco', 'braco');
    }

    public function mwarco()
    {
        return $this->belongsTo(Mwarco::class, 'warco', 'warco');
    }

    public function mpromas()
    {
        return $this->belongsTo(Mpromas::class, 'opron', 'opron');
    }
}