<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    use HasFactory;
    public $table = "provinsi";
    protected $keyType = 'string';
    protected $primaryKey = 'id_prov';

    public function mcusmascbg(){
        return $this->hasOne(McusmasCbg::class,'provinsi','id_prov');
    }
    // public function mcusmas()
    // {
    //     return $this->hasMany(Mcusmas::class,'provinsi');
    // }
}
