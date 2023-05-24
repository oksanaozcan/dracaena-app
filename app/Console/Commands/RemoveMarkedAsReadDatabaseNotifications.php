<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\Notification\RemoveReadedDbNotificationsJob;

class RemoveMarkedAsReadDatabaseNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remove-marked-as-read-database-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear table "notifications" from marked as read notifications';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $ids = DB::table('notifications')->where('read_at','!=', null)->pluck('id');
        RemoveReadedDbNotificationsJob::dispatch($ids);
    }
}
