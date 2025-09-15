<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class McusmasCbg extends Model
{
    use HasFactory;
    public $table = "tcustomer_cbg_tbl";
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['customer_id','lokasi','alamat','telp','fax','email','kontak','provinsi','telp_kontak','kabupaten'];

    // public function provinsi(){
    //     return $this->belongsTo(Provinsi::class,'id_prov','provinsi');
    // }
}
