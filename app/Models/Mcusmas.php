<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mcusmas extends Model
{
    use HasFactory;
    //public $table = "tcustomer_tbl";
    public $table = "mcusmas";
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';
    //protected $primaryKey = 'customer_id';
    protected $primaryKey = 'cusno';
    //protected $fillable = ['groupp','title','nama_perusahaan','lokasi','alamat','provinsi','kabupaten','telp','fax','email','kontak','telp_kontak','braco','depo','user_','inputdate'];
    protected $guarded = [];

    // public function mcusmasdet(): HasOne
    // {
    //     //return $this->hasOne(McusmasDet::class);
    // }
}
