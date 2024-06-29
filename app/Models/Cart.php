<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'product_id'];

    protected $table = 'carts';

    public function order()
    {
        return $this->belongsTo(Order::class, 'client_id', 'client_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }

}
