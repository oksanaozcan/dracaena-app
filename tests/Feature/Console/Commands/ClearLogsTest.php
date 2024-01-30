<?php

namespace Tests\Feature\Console\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ClearLogsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        File::put(storage_path('logs/test1.log'), 'Test log content');
        File::put(storage_path('logs/test2.log'), 'Test log content');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        File::delete([
            storage_path('logs/test1.log'),
            storage_path('logs/test2.log'),
        ]);
    }

    public function test_1_it_clears_logs_from_storage_path()
    {
        Artisan::call('app:clear-logs');

        Storage::disk('local')->assertMissing('logs/test1.log');
        Storage::disk('local')->assertMissing('logs/test2.log');

        $this->assertStringContainsString('Logs have been cleared!', Artisan::output());
    }

    public function test_2_it_handles_empty_logs_directory()
    {
        File::delete([
            storage_path('logs/test1.log'),
            storage_path('logs/test2.log'),
        ]);

        Artisan::call('app:clear-logs');

        $this->assertStringContainsString('Logs have been cleared!', Artisan::output());
    }
}

