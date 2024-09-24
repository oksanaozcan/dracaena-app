<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGroupBySize extends Model
{
    use HasFactory;

    protected $table = 'product_group_by_sizes';

    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
