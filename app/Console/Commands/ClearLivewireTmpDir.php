<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearLivewireTmpDir extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-livewire-tmp-dir';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all files from storage/app/livewire-tmp directory';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Storage::delete(Storage::files('livewire-tmp'));
        $this->info('Temporary files were successfully deleted');
    }
}
