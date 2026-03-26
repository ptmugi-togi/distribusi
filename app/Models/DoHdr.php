<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoHdr extends Model
{
    use HasFactory;

    protected $table = 'tsisnh';
    protected $primaryKey = 'bbkid';
    protected $keyType = 'string';
    public $increment = false;
    public $timestamps = true;

    protected $fillable = [
        'bbkid',
        'bracoformc',
        'braco',
        'warco',
        'formc',
        'trano',
        'tradt',
        'priod',
        'rqbrc', 
        'cusno', 
        'cuspo',  
        'rfc01',
        'ref01',
        'exped',
        'prctr',
        'noteh',
        'shpto',
        'supno',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'user_id',
    ];

    public function dodtls()
    {
        return $this->hasMany(DoDtl::class, 'bbkid', 'bbkid');
    }

    public function mbranch()
    {
        return $this->belongsTo(Mbranch::class, 'rqbrc', 'braco');
    }

    public function mformcode()
    {
        return $this->belongsTo(Mformcode::class, 'bracoformc', 'bracoformc');
    }

    public function mcusmas()
    {
        return $this->belongsTo(Mcusmas::class, 'cusno', 'cusno');
    }

    public function mstmas()
    {
        return $this->belongsTo(Mstmas::class, 'cusno', 'cusno')
        ->whereColumn('mstmas.shpto', 'dohdr.shpto');
    }

    public function mpromas()
    {
        return $this->belongsTo(Mpromas::class, 'opron', 'opron');
    }
}