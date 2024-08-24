<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;
    use Filterable;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function categoryFilter(): BelongsTo
    {
        return $this->belongsTo(CategoryFilter::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags', 'product_id', 'tag_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'product_id', 'id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    /**
     * Get the favourites for the product.
     */
    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }

    /**
     * Get the customers who have marked this product as a favorite.
     */
    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'favourites')
                    ->withTimestamps();
    }

    /**
     * Get the customers who have added this product in own cart.
     */
    public function addedToCartBy(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'carts')
                    ->withTimestamps();
    }

    public function restokeSubscriptions()
    {
        return $this->hasMany(RestokeSubscription::class);
    }

    public function restokeSubscribedBy(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'restoke_subscriptions')
                    ->withTimestamps();
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
