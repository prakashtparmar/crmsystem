<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expiry extends Model
{
    use SoftDeletes;

    protected $fillable = ['batch_lot_id','expiry_date','is_expired'];

    protected $dates = ['expiry_date'];

    public function batchLot()
    {
        return $this->belongsTo(BatchLot::class);
    }
}
