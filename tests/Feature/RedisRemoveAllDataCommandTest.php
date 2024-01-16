<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;
use Illuminate\Support\Facades\Redis;

class RedisRemoveAllDataCommandTest extends TestCase
{
    protected $message = 'Flushing the cache does not respect your configured cache "prefix" and will remove all entries from the cache. Consider this carefully when clearing a cache which is shared by other applications. Do you want to remove cache?';

    public function test_1_it_removes_all_data_from_redis_cache()
    {
         Cache::shouldReceive('flush')->once();

         $this->artisan('redis:flush')
             ->expectsQuestion($this->message, 'yes')
             ->expectsOutput('All Redis Cache removed')
             ->assertExitCode(0);
    }

    public function test_2_it_cancels_operation_when_user_says_no()
    {
        Cache::shouldReceive('flush')->andReturnUsing(function () {
            throw new \Exception('Cache::flush should not be called');
        });

        $this->artisan('redis:flush')
            ->expectsQuestion($this->message, 'no')
            ->expectsOutput('Cancel removing')
            ->assertExitCode(0);

        $this->assertTrue(true);
    }
}
