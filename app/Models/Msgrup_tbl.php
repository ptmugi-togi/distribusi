<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Msgrup_tbl extends Model
{
    use HasFactory;
    public $table = "msgrup_tbl";
    public $timestamps = false;
    protected $keyType = 'string';
    protected $primaryKey = 'sgrup_id';
    protected $fillable = ['sgrup_id','descr_sgrup'];
}
