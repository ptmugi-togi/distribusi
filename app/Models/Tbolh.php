<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbolh extends Model
{
    use HasFactory;

    protected $table = 'tbolh';
    protected $primaryKey = 'rinum';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = true;
    protected $fillable = [
        'braco',
        'formc',
        'rinum',
        'nocal',
        'blnum',
        'bldat',
        'supno',
        'vesel',
        'shpln',
        'clrag',
        'etds',
        'etah',
        'etaw',
        'gweight',
        'nweight',
        'npiud',
        'tpiud',
        'npolis',
        'tpolis',
        'lcpcm',
        'sts02',
        'fkdate',
        'pload',
        'pdest',
        'facfk',
        'ratebcd',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'user_id',
    ];

    protected static function booted()
    {
        static::deleting(function ($tbolh) {
            $tbolh->tbold()->delete();
        });
    }

    public function tbold()
    {
        return $this->hasMany(Tbold::class, 'rinum', 'rinum');
    }

    public function vendor()
    {
        return $this->belongsTo(Mvendor::class, 'supno', 'supno');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function formcode()
    {
        return $this->belongsTo(Mformcode::class, 'formc', 'formc');
    }

    public function branches()
    {
        return $this->belongsTo(Mbranch::class, 'braco', 'braco');
    }

    public function currency()
    {
        return $this->belongsTo(Mcurco::class, 'curco', 'curco');
    }
}