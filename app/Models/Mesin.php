<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesin extends Model
{
    use HasFactory;
    public $table = "tmesin_tbl";
    public $timestamps = false;
    protected $primaryKey = 'idmesin';
    protected $fillable = ['idmesin','customer_id','type','kapasitas','serial_num','intalled','status','brand','product','akhir_tera','id_cus_cbg','platform_size','awal_mc_garansi','akhir_mc_garansi','no_garansi','indikator','sn_indikator','produk','printer','load_cell','aksesoris','id_m'];
}
