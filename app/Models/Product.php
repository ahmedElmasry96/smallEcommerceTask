<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'image', 'price', 'quantity', 'discount_value'];

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('name', 'LIKE', "%{$searchTerm}%");
    }

    public function scopePriceRange($query, $minPrice, $maxPrice)
    {
        return $query->when($minPrice, function($query, $minPrice) {
            return $query->where('price', '>=', $minPrice);
        })->when($maxPrice, function($query, $maxPrice) {
            return $query->where('price', '<=', $maxPrice);
        });
    }
}
