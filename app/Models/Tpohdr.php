<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TpoHdr extends Model
{
    use HasFactory;

    protected $table = 'pohdr_tbl';
    protected $primaryKey = 'pono';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;
    protected $fillable = [
        'pono',
        'formc',
        'podat',
        'potype',
        'topay',
        'tdesc',
        'curco',
        'shvia',
        'sconp',
        'delco',
        'diper',
        'vatax',
        'stamp',
        'noteh',
        'supno',
        'user_id',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public function vendor()
    {
        return $this->belongsTo(Mvendor::class, 'supno', 'supno');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tpodtl()
    {
        return $this->hasMany(TpoDtl::class, 'pono', 'pono');
    }

    public function formcode()
    {
        return $this->belongsTo(Mformcode::class, 'formc', 'formc');
    }

    public function branches()
    {
        return $this->belongsTo(Mbranch::class, 'braco', 'braco');
    }
}