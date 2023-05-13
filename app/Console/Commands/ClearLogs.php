<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearLogs extends Command
{
    protected $signature = 'app:clear-logs';

    protected $description = 'Remove all logs from store/laravel.log file';

    public function handle(): void
    {
        exec('rm -f ' . storage_path('logs/*.log'));

        exec('rm -f ' . base_path('*.log'));

        $this->comment('Logs have been cleared!');
    }
}
