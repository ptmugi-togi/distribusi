<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mlocco extends Model
{
    use HasFactory;
    protected $table = 'mlocco_tbl';
    protected $primaryKey = 'idco';
    public $timestamps = false;
    public $incrementing = true;

    protected $fillable = [
        'idco',
        'warco',
        'locco',
        'descr'
    ];

    public function bbmhdr()
    {
        return $this->belongsTo(BbmHdr::class, 'locco', 'locco');
    }
}
