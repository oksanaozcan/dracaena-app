<?php

namespace Tests\Feature\Providers;
use Illuminate\Support\Facades\Broadcast;
use Tests\TestCase;

class BroadcastServiceProviderTest extends TestCase
{
    public function test_1_it_requires_channels_file_to_be_required()
    {
        $this->assertTrue(file_exists(base_path('routes/channels.php')));
    }

    public function test_2_it_can_boot_without_errors()
    {
        $provider = new \App\Providers\BroadcastServiceProvider($this->app);
        $provider->boot();

        $this->assertTrue(true);
    }
}
