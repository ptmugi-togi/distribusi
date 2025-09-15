<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mpromas extends Model
{
    public $table = "mpromas";
    use HasFactory;
    public $timestamps = false;
    protected $keyType = 'integer';
    protected $primaryKey = 'mproma';
    protected $fillable = ['status','opron','prona','nama_supplier','stdqu','itype_id','brand_name','pgrup','sgrup_id','ssgrup_id','lssgrup','weigh','meast','measl','measp','volum','abccl','capac','platf','mstok','spnum','garansi','pbilp','ijtype','id_cls'];
}
