<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certification extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id','type','certificate_no','issued_by',
        'valid_from','valid_to','document_path'
    ];

    protected $dates = ['valid_from','valid_to'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
