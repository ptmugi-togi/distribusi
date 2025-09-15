<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class McusmasDet extends Model
{
    use HasFactory;
    public $table = "tcustomer_det";
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['customer_id','customer_cbg_id','npwp','nik'];

    public function mcusmas(): BelongsTo
    {
        return $this->belongsTo(Mcusmas::class,"customer_id");
    }
}
