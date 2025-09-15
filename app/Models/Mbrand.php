<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mbrand extends Model
{
    public $table = "mbrand_tbl";
    use HasFactory;
    public $timestamps = false;
    // protected $keyType = 'string';
    // protected $primaryKey = 'brand_id';
    protected $fillable = ['brand_name','descr_brand','topay'];
}
