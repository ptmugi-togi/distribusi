<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OcDtl extends Model
{
    use HasFactory;

    protected $table = 'tcored';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $increment = true;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'ocid',
        'braco',
        'formc',
        'sorno',
        'opron',
        'prona',
        'stdqu',
        'putama',
        'qtyor',
        'qtydo',
        'qtyin',
        'tobed',
        'plist',
        'price',
        'odisp',
        'odisa',
        'teknik',
        'rqeta',
        'whetd',
        'srcog',
        'noted',
        'cancp',
        'cancd',
    ];

    public function ochdr()
    {
        return $this->hasMany(OcHdr::class, 'ocid', 'ocid');
    }

    public function mpromas()
    {
        return $this->belongsTo(Mpromas::class, 'opron', 'opron');
    }
}