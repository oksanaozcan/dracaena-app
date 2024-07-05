<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Models\Product;
use App\Observers\ProductObserver;
use App\Events\ProductOutOfStock;
use App\Listeners\HandleProductOutOfStock;
use App\Models\Cart;
use App\Observers\CartObserver;
use App\Models\Favourite;
use App\Observers\FavouriteObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ProductOutOfStock::class => [
            HandleProductOutOfStock::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Product::observe(ProductObserver::class);
        Cart::observe(CartObserver::class);
        Favourite::observe(FavouriteObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
