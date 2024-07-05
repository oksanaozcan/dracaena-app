<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory, SoftDeletes, Prunable;

    protected $table = 'orders';
    protected $guarded = [];

    public function prunable(): Builder
    {
        return static::where('deleted_at', '<=', now()->subMonths(3));
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->using(OrderProduct::class)->withTimestamps();
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id', 'id');
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id', 'id');
    }
}
