<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mtaxes extends Model
{
    use HasFactory;

    protected $table = 'mtaxes';
    protected $primaryKey = 'braco';
    protected $keyType = 'string';
    public $increment = false;
    public $timestamps = false;

    protected $fillable = ['braco','taxes','nama','jabatan'];
}
