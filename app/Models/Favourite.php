<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Favourite extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'product_id'];

    protected $table = 'favourites';

     /**
     * Get the customer that owns the favourite.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the product that is favourited.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
