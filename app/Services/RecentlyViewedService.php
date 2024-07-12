<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class RecentlyViewedService
{
    protected $cacheTime = 3600;

    public function addRecentlyViewed($productId, $customerId)
    {
        $cacheKey = "recently_viewed_{$customerId}";

        $recentlyViewed = Cache::get($cacheKey, []);
        $recentlyViewed = array_filter($recentlyViewed, fn($id) => $id !== $productId); // Remove if exists
        array_unshift($recentlyViewed, $productId); // Add to the beginning
        $recentlyViewed = array_slice($recentlyViewed, 0, 10); // Keep only the latest 10 items

        Cache::put($cacheKey, $recentlyViewed, $this->cacheTime);
    }

    public function getRecentlyViewed($customerId)
    {
        $cacheKey = "recently_viewed_{$customerId}";
        return Cache::get($cacheKey, []);
    }
}
