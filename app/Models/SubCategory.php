<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $fillable = ['category_id','name', 'logo', 'description'];
    protected $casts = [
        'category_id' => 'integer',
        'logo' => 'string',
        'description' => 'string',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
