<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mexped extends Model
{
    use HasFactory;

    protected $table = 'mexped';
    protected $primaryKey = 'ename';
    protected $keyType = 'string';
    public $increment = false;
    public $timestamps = false;

    protected $fillable = [
        'ename',
        'address',
        'phone',
        'fax',
        'contp',
        'email',
    ];
}