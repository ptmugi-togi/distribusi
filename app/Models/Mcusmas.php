<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mcusmas extends Model
{
    //public $table = "tcustomer_tbl";
    public $table = "mcusmas";
    use HasFactory;
    public $timestamps = false;
    //protected $primaryKey = 'customer_id';
    protected $primaryKey = 'id';
    //protected $fillable = ['groupp','title','nama_perusahaan','lokasi','alamat','provinsi','kabupaten','telp','fax','email','kontak','telp_kontak','braco','depo','user_','inputdate'];
    protected $guarded = [];

    // public function mcusmasdet(): HasOne
    // {
    //     //return $this->hasOne(McusmasDet::class);
    // }
}
