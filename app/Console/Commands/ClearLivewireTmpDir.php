<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearLivewireTmpDir extends Command
{
    protected $signature = 'app:clear-livewire-tmp-dir';

    protected $description = 'Delete all files from storage/app/livewire-tmp directory';

    public function handle(): void
    {
        $files = Storage::allFiles('livewire-tmp');
        if (empty($files)) {
            $this->info('No files found in the livewire-tmp directory');
        } else {
            Storage::delete(Storage::files('livewire-tmp'));
            $this->info('Temporary files were successfully deleted');
        }
    }
}
