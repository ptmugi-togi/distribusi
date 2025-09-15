<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mdepo extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $primaryKey = 'depo';
    protected $fillable = ['depo','name','braco','address','cont','email','phone','faxno','npwp','pkp','tglsk'];
}
