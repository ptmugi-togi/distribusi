<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mwarco extends Model
{
    use HasFactory;

    protected $table = 'mwarco_tbl';
    protected $primaryKey = 'warco';
    protected $keyType = 'string';
    public $increment = false;
    public $timestamps = false;

    protected $fillable = [
        'warco',
        'warna',
        'contp',
        'address',
        'email',
        'phone',
        'faxno',
        'alloc',
        'braco'
    ];

}