<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DnDtl extends Model
{
    use HasFactory;

    protected $table = 'tdna';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $increment = true;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'dnid',
        'braco',
        'formc',
        'dnnum',
        'dnlin',
        'tofee',
        'descr',
        'opron',
        'stdqu',
        'trqty',
        'lotno',
        'gramt',
        'odisp',
        'odisa',
        'netbe',
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