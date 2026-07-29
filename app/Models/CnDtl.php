<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CnDtl extends Model
{
    use HasFactory;

    protected $table = 'tcnd';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $increment = true;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'cnid',
        'bracoformc',
        'braco',
        'formc',
        'crnno',
        'crnln',
        'opron',
        'prona',
        'stdqu',
        'qtycn',
        'price',
        'gramt',
        'odisp',
        'odisa',
        'dpamt',
        'noted',
        'ntamt'
    ];

    public function cnhdr()
    {
        return $this->belongsTo(CnHdr::class, 'cnid', 'cnid');
    }
}