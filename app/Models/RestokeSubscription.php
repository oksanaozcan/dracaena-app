<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestokeSubscription extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'product_id'];

    protected $table = 'restoke_subscriptions';

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
