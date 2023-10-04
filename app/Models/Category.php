<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CategoryFilter;
use App\Models\Tag;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'preview'];

    // protected $with = ['categoryFilters'];

    public function products()
    {
      return $this->hasMany(Product::class, 'category_id', 'id');
    }

    public function categoryFilters()
    {
      return $this->hasMany(CategoryFilter::class, 'category_id', 'id');
    }

    public function tags()
    {
        return $this->hasManyThrough(Tag::class, CategoryFilter::class);
    }

}
