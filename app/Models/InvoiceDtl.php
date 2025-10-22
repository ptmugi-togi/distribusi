<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoiceDtl extends Model
{
    use HasFactory;

    protected $table = 'tsupid_tbl';
    protected $primaryKey = 'id_su';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $fillable = [
        'invno',
        'pono',
        'opron',
        'inqty',
        'stdqt',
        'inamt',
        'ewprc',
        'fobch',
        'frcst',
        'incst',
        'hsn',
        'bm',
        'ppn',
        'ppnbm',
        'pph',
    ];

    public function tsupih()
    {
        return $this->belongsTo(Tbolh::class, 'invno', 'invno');
    }
}