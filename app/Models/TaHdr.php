<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaHdr extends Model
{
    use HasFactory;

    protected $table = 'tsisnh';
    protected $primaryKey = 'bbkid';
    protected $keyType = 'string';
    public $increment = false;
    public $timestamps = true;

    protected $fillable = [
        'bbkid',
        'braco',
        'warco',
        'formc',
        'trano',
        'tradt',
        'priod',
        'rqbrc',    
        'rfc01',
        'ref01',
        'exped',
        'prctr',
        'noteh',
        'supno',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'user_id',
    ];

    public function tadtls()
    {
        return $this->hasMany(TaDtl::class, 'bbkid', 'bbkid');
    }

    public function mbranch()
    {
        return $this->belongsTo(Mbranch::class, 'rqbrc', 'braco');
    }

    public function mwarco()
    {
        return $this->belongsTo(Mwarco::class, 'delco', 'warco');
    }

    public function mformcode()
    {
        return $this->belongsTo(Mformcode::class, 'bracoformc', 'bracoformc');
    }

    public function mpromas()
    {
        return $this->belongsTo(Mpromas::class, 'opron', 'opron');
    }
}