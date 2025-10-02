<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbolh extends Model
{
    use HasFactory;

    protected $table = 'tbold';
    protected $primaryKey = 'id_bl';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;
    protected $fillable = [
        'id_bl',
        'rinum',
        'costt',
        'costf',
    ];

    public function tbolh()
    {
        return $this->belongsTo(tbolh::class, 'rinum', 'rinum');
    }
}