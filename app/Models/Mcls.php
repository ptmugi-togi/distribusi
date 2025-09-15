<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mcls extends Model
{
    public $table = "mcls_tbl";
    use HasFactory;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id_cls';
    protected $fillable = ['id_cls','class','descr_cls'];
}
