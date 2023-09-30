<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CategoryFilter;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'preview'];

    public function products()
    {
      return $this->hasMany(Product::class, 'category_id', 'id');
    }

    public function categoryFilters()
    {
      return $this->hasMany(CategoryFilter::class, 'category_id', 'id');
    }

}
