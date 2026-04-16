<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tsisnh extends Model
{
    protected $table = 'tsisnh';
    public $timestamps = false;

    protected $primaryKey = 'bbkid';
    public $incrementing = false;
}