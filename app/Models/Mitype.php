<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitype extends Model
{
    public $table = "mitype_tbl";
    use HasFactory;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $primaryKey = 'itype_id';
    protected $fillable = ['itype_id','descr_itype'];
}
