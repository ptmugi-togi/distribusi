<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mssgrup_tbl extends Model
{
    public $table = "mssgrup";
    public $timestamps = false;
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['ssgrup_id','descr_ssgrup'];
    protected $primaryKey = 'ssgrup_id';
}
