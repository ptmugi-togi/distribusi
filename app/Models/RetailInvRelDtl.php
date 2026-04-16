<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetailInvRelDtl extends Model
{
    use HasFactory;

    protected $table = 'tindet';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $increment = true;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'invid',
        'braco',
        'formc',
        'invno',
        'sorfc',
        'sorno',
        'isite',
        'sorli',
        'opron',
        'prona',
        'stdqu',
        'lotno',
        'qtyin',
        'price',
        'gramt',
        'odisp',
        'odisa',
        'netbe',
        'dpper',
        'dpamt',
        'edisp',
        'edisa',
        'netae',
        'sqtb1',
        'sqts1',
        'sqp1',
        'sqtb2',
        'sqts2',
        'sqp2',
        'csect',
        'ortyp'
    ];

    public function retailinvrelhdr()
    {
        return $this->hasMany(RetailInvRelHdr::class, 'invid', 'invid');
    }

    public function mpromas()
    {
        return $this->belongsTo(Mpromas::class, 'opron', 'opron');
    }
}