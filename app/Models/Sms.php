<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    use HasFactory;

    protected $table = 'stobb_tbl';
    protected $primaryKey = 'idbb';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;
    protected $fillable = [
        'idbb',
        'formc',
        'podat',
        'user_id',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    public function branches()
    {
        return $this->belongsTo(Mbranch::class, 'braco', 'braco');
    }
}