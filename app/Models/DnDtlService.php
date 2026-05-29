<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DnDtlService extends Model
{
    protected $table = 'tdnb';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'dnid',
        'braco',
        'formc',
        'dnnum',
        'dnlin',
        'serty',
        'tofee',
        'descr',
        'gramt',
        'odisp',
        'odisa',
        'net',
    ];

    public function dnhdr()
    {
        return $this->belongsTo(DnHdr::class, 'dnid', 'dnid');
    }
}