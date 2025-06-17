<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'sub_category_id',
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'stock',
        'sku',
        'barcode',
        'image',
        'is_active',
        'is_featured',
        'is_new',
        'is_best_seller',
        'is_on_sale',
        'specifications',
    ];
    protected $casts = [
        'image'=>'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_best_seller' => 'boolean',
        'is_on_sale' => 'boolean',
        'specifications' => 'array',

    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
    // public function specifications()
    // {
    //     return $this->hasMany(Specification::class);
    // }
    // public function productSpecifications()
    // {
    //     return $this->hasMany(ProductSpecification::class);
    // }
}
