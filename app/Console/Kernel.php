<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\KernelTestingTrait;

class Kernel extends ConsoleKernel
{
    use KernelTestingTrait;
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('app:clear-livewire-tmp-dir')->daily()->at('07:00');
        $schedule->command('app:remove-marked-as-read-database-notifications')->weekly();
        $schedule->command('model:prune')->daily();
        $schedule->command('passport:purge --revoked')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
