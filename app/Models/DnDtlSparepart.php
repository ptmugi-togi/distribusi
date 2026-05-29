<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DnDtlSparepart extends Model
{
    protected $table = 'tdnc';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'dnid',
        'braco',
        'formc',
        'dnnum',
        'opron',
        'lotno',
        'warco',
        'locco',
        'trqty',
        'qunit',
        'price',
        'gramt',
        'odisp',
        'odisa',
        'netbe',
        'descr',
    ];

    public function dnhdr()
    {
        return $this->belongsTo(DnHdr::class, 'dnid', 'dnid');
    }

    public function mpromas()
    {
        return $this->belongsTo(Mpromas::class, 'opron', 'opron');
    }
}