<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mcindu extends Model
{
    public $table = "mcindu_tbl";
    use HasFactory;
    public $timestamps = false;
    //protected $keyType = 'string';
    protected $primaryKey = 'cindu';
    protected $fillable = ['descr_cindu'];
}
