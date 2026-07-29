<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CnHdr extends Model
{
    use HasFactory;

    protected $table = 'tcnh';
    protected $primaryKey = 'cnid';
    protected $keyType = 'string';
    public $increment = false;
    public $timestamps = true;

    protected $fillable = [
        'cnid',
        'bracoformc',
        'braco',
        'warco',
        'formc',
        'crnno',
        'crndt',
        'priod',
        'notar',
        'cusno',
        'invfc',
        'invno',
        'ortyp',
        'vatax',
        'curco',
        'crate',
        'gramt',
        'dpamt',
        'odisa',
        'ntamt',
        'txamt',
        'cramt',
        'lauid',
        'reaso',
        'warco',
        'srnfc',
        'srnno',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    public function cndtls()
    {
        return $this->hasMany(CnDtl::class, 'cnid', 'cnid');
    }

    public function mbranch()
    {
        return $this->belongsTo(Mbranch::class, 'braco', 'braco');
    }

    public function mwarco()
    {
        return $this->belongsTo(Mwarco::class, 'warco', 'warco');
    }

    public function mformcode()
    {
        return $this->belongsTo(Mformcode::class, 'bracoformc', 'bracoformc');
    }

    public function customer()
    {
        return $this->belongsTo(Mcusmas::class, 'cusno', 'cusno');
    }
}