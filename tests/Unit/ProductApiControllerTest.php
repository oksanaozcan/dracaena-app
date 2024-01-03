<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ProductApiControllerTest extends TestCase
{
    public function test_cache_usage(): void
    {
         // Create a mock IndexRequest with valid data
    $request = // create an instance of your IndexRequest with valid data;

    // Mock the Cache to assert if it is utilized
    Cache::shouldReceive('tags->remember')->once();

    $controller = new ProductApiController();
    $response = $controller->index($request);

    // Add assertions to check if the cache was utilized
    }
}
