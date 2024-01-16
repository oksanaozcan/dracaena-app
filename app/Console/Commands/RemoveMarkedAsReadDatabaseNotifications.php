<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\Notification\RemoveReadedDbNotificationsJob;

class RemoveMarkedAsReadDatabaseNotifications extends Command
{
    protected $signature = 'app:remove-marked-as-read-database-notifications';

    protected $description = 'Clear table "notifications" from marked as read notifications';

    public function handle(): void
    {
        $ids = DB::table('notifications')->where('read_at','!=', null)->pluck('id');
        RemoveReadedDbNotificationsJob::dispatch($ids);
    }
}
