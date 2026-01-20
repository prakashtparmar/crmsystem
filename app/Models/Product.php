<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id','subcategory_id','brand_id','unit_id','crop_id','season_id',
        'name','slug','short_description','description',
        'hsn_code','gst_percent','is_organic',
        'min_order_qty','max_order_qty','shelf_life_days','is_active'
    ];

    public function category()     { return $this->belongsTo(Category::class); }
    public function subcategory()  { return $this->belongsTo(SubCategory::class); }
    public function brand()        { return $this->belongsTo(Brand::class); }
    public function unit()         { return $this->belongsTo(Unit::class); }
    public function crop()         { return $this->belongsTo(Crop::class); }
    public function season()       { return $this->belongsTo(Season::class); }

    public function variants()     { return $this->hasMany(ProductVariant::class); }
    public function attributes()   { return $this->hasMany(ProductAttribute::class); }
    public function images()       { return $this->hasMany(ProductImage::class); }
    public function tags()         { return $this->hasMany(ProductTag::class); }
    public function certifications(){ return $this->hasMany(Certification::class); }
    public function stocks()
{
    return $this->hasMany(ProductStock::class);
}

}
