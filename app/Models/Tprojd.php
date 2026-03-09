<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tprojd extends Model
{
    protected $table = 'tprojd';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'ocsbid',
        'braco',
        'formc',
        'sorno',
        'phase',
        'descr',
        'toppc',
        'gross',
        'odisa',
        'edisa',
        'ntamt',
        'blamt',
        'ebamt',
        'billd',
        'invfc',
        'invno',
        'invdt',
        'itext',
        'updqt',
        'sperf',
        'iperf',
        'sts01',
        'smqp1',
        'smqtb1',
        'smqts1',
        'smqp2',
        'smqtb2',
        'smqts2',
        'smqp3',
        'smqtb3',
        'smqts3',
        'smqp4',
        'smqtb4',
        'smqts4',
        'smqp5',
        'smqtb5',
        'smqts5',
        'noted'
    ];
}