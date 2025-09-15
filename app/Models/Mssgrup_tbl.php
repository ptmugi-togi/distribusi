<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mssgrup_tbl extends Model
{
    public $table = "mssgrup_tbl";
    public $timestamps = false;
    use HasFactory;
    protected $fillable = ['descr_ssgrup'];
    protected $primaryKey = 'ssgrup_id';
}
