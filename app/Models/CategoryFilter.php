<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryFilter extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function tags()
    {
      return $this->hasMany(Tag::class, 'category_filter_id', 'id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
