<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mvendor extends Model
{
    protected $table = 'mvendor_tbl';
    protected $primaryKey = 'supno';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    // isi sesuai kolom nyata di mvendor_tbl
    protected $fillable = ['supno','supna'];
}
