<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mbranch extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $primaryKey = 'braco';
    protected $fillable = ['braco','brana','conam','address','contactp','phone','faxno','npwp','tglsk','email'];
}
