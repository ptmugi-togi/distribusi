<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mczone extends Model
{
    use HasFactory;
    public $table = "mczone_tbl";
    public $timestamps = false;
    protected $keyType = 'string';
    protected $primaryKey = 'czone';
    protected $guarded = [];
}
