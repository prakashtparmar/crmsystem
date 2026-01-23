<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Unit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'symbol',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Automatically generate slug when creating/updating.
     */
    protected static function booted()
    {
        static::creating(function ($unit) {
            if (empty($unit->slug)) {
                $unit->slug = Str::slug($unit->name);
            }
        });

        static::updating(function ($unit) {
            if ($unit->isDirty('name')) {
                $unit->slug = Str::slug($unit->name);
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
