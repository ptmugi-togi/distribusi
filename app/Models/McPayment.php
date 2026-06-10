<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class McPayment extends Model
{
    use HasFactory;

    protected $table = 'tmcd2';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $increment = true;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'mcid',
        'braco',
        'depo',
        'formc',
        'refno',
        'phase',
        'descr',
        'toppc',
        'gramt',
        'odisa',
        'odisp',
        'ntamt',
        'vatax',
        'txamt',
        'blamt',
        'billd',
        'invfc',
        'invno',
        'wdelto',
        'winvdt',
        'wduedt',
        'wpriod',
        'sts01',
        'witext',
    ];

    public function mchdr()
    {
        return $this->belongsTo(McHdr::class, 'mcid', 'mcid');
    }
}