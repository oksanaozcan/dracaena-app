<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'category_filter_id'];

    public function categoryFilter()
    {
        return $this->belongsTo(CategoryFilter::class, 'category_filter_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tags', 'tag_id', 'product_id');
    }

    public function category()
    {
        return $this->categoryFilter->category;
    }

}
