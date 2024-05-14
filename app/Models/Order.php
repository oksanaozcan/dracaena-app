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

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'clerk_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->using(OrderProduct::class)->withTimestamps();
    }

    public function cart()
    {
        return $this->hasOne(Cart::class, 'client_id', 'client_id');
    }
}
