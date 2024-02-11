<?php

namespace Tests\Feature\Http\Middleware;

use App\Http\Middleware\TrustHosts;
use Illuminate\Contracts\Config\Repository;
use PHPUnit\Framework\TestCase;

class TrustHostsTest extends TestCase
{
    public function test_it_returns_trusted_hosts()
    {
        // Mock the configuration repository
        $configRepository = $this->createMock(Repository::class);
        // Set up the behavior of the get method to return a dummy URL
        $configRepository->method('get')
            ->with('app.url')
            ->willReturn('http://example.com');

        // Create an instance of TrustHosts middleware with the mocked configuration repository
        $middleware = new TrustHosts($configRepository);

        // Call the hosts method to get trusted hosts
        $trustedHosts = $middleware->hosts();

        // Assert that trusted hosts are returned
        $this->assertIsArray($trustedHosts);
        $this->assertNotEmpty($trustedHosts);
    }
}
