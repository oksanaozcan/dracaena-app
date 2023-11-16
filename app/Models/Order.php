<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders';
    protected $guarded = [];

    public function prunable()
    {
        return static::where('deleted_at', '<=', now()->subMonth());
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'clerk_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
        ->withPivot('amount');
    }
}
