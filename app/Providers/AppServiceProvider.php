<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Cashier\Cashier;
use App\Models\Client;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // JsonResource::withoutWrapping();

        Builder::macro('search', function ($field, $string) {
            return $string ? $this->where($field, 'like', '%'.$string.'%') : $this;
        });

        EloquentBuilder::macro('softDeleted', function () {
            return $this->onlyTrashed();
        });

        Builder::macro('toCsv', function () {
            $results = $this->get();

            if ($results->empty()) return;
        });

        ini_set('memory_limit', '512M');

        Cashier::useCustomerModel(Client::class);

        // Passport::hashClientSecrets();
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::ignoreRoutes();
    }
}
