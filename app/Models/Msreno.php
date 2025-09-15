<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Msreno extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['braco','sreno','srena','steam','address','phone','grade','aktif'];
}
