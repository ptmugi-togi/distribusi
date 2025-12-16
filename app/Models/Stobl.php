<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stobl extends Model
{
    use HasFactory;

    protected $table = 'stobl_tbl';
    protected $primaryKey = 'idbl';
    protected $keyType = 'int';
    public $increment = true;
    public $timestamps = false;

    protected $fillable = [
        'idbl',
        'braco',
        'warco',
        'opron',
        'qunit',
        'locco',
        'lotno',
        'bbqoh',
        'toqoh',
    ];

    public function stobw()
    {
        return $this->belongsTo(Stobw::class, 'braco', 'braco');
    }
}