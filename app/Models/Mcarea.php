<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mcarea extends Model
{
    use HasFactory;
    public $table = "mcarea_tbl";
    public $timestamps = false;
    protected $primaryKey = 'id_area';
    protected $guarded = [];
}
