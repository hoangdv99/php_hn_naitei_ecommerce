<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'regular_price',
        'quantity',
        'SKU',
        'category_id',
    ];

    public function images()
    {
        return $this->hasMany(Images::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
