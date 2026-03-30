<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mssgrup extends Model
{
    public $table = "mssgrup";
    public $timestamps = false;
    use HasFactory;
    protected $fillable = ['descr_ssgrup'];
    protected $primaryKey = 'ssgrup_id';
}
