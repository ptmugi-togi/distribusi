<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class msgrup extends Model
{
    use HasFactory;
    public $table = "msgrup";
    public $timestamps = false;
    protected $keyType = 'string';
    protected $primaryKey = 'sgrup_id';
    protected $fillable = ['sgrup_id','descr_sgrup'];
}
