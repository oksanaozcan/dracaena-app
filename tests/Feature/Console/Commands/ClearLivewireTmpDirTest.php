<?php

namespace Tests\Feature\Console\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class ClearLivewireTmpDirTest extends TestCase
{
    public function test_1_it_deletes_files_from_livewire_tmp_directory()
    {
        Storage::put('livewire-tmp/test1.txt', 'content');
        Storage::put('livewire-tmp/test2.txt', 'content');

        Artisan::call('app:clear-livewire-tmp-dir');

        $this->assertFalse(Storage::exists('livewire-tmp/test1.txt'));
        $this->assertFalse(Storage::exists('livewire-tmp/test2.txt'));

        $this->assertStringContainsString('Temporary files were successfully deleted', Artisan::output());
    }

    public function test_2_it_handles_empty_livewire_tmp_directory()
    {
        Artisan::call('app:clear-livewire-tmp-dir');
        $this->assertStringContainsString('No files found in the livewire-tmp directory', Artisan::output());
    }

}
