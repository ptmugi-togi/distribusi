<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mstmas extends Model
{
    use HasFactory;
    public $table = 'mstmas';
    protected $primaryKey = 'id';
    protected $keyType = 'integer';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = ['braco','cusno','shpto','shpnm','deliveryaddress','phone','fax','contp','province','kabupaten'];
}
