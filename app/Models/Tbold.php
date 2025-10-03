<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbold extends Model
{
    use HasFactory;

    protected $table = 'tbold';
    protected $primaryKey = 'id_bl';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;
    protected $fillable = [
        'rinum',
        'costt',
        'costf',
    ];

    public function tbolh()
    {
        return $this->belongsTo(Tbolh::class, 'rinum', 'rinum');
    }
}