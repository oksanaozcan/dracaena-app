<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Customer extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;
    use Notifiable;

    protected $table = 'customers';

    protected $fillable = [
        'name',
        'email',
        'password',
        'birthday',
        'newsletter_confirmed',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function shippingAddress()
    {
        return $this->hasOne(Address::class)
            ->where('type', 'shipping')
            ->where('specified_in_order', false);
    }

    public function billingAddress()
    {
        return $this->hasOne(Address::class)
            ->where('type', 'billing')
            ->where('specified_in_order', false);
    }

     /**
     * Get the products that are marked as favorites by the customer.
     */
    public function favoriteProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'favourites')
                    ->withTimestamps();
    }

      /**
     * Get the products that are marked as in cart by the customer.
     */
    public function cartProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'carts')
                    ->withTimestamps();
    }
}
