<?php

namespace Tests\Feature;

use App\Jobs\Notification\RemoveReadedDbNotificationsJob;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Mockery;

class RemoveMarkedAsReadDatabaseNotificationsTest extends TestCase
{
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
    */
    public function test_1_it_dispatches_job_with_ids_of_read_notifications()
    {
        $mockedIds = [1, 2, 3];
        $dbMock = Mockery::mock('alias:Illuminate\Support\Facades\DB');
        $dbMock->shouldReceive('table')->with('notifications')->andReturnSelf();
        $dbMock->shouldReceive('where')->with('read_at', '!=', null)->andReturnSelf();
        $dbMock->shouldReceive('pluck')->with('id')->andReturn($mockedIds);

        Queue::fake();

        Artisan::call('app:remove-marked-as-read-database-notifications');

        Queue::assertPushed(RemoveReadedDbNotificationsJob::class, function ($job) use ($mockedIds) {
            return $job->ids === $mockedIds;
        });

        Mockery::close();
    }
}
