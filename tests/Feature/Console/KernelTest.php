<?php

namespace Tests\Feature\Console;

use App\Console\Kernel;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Console\Scheduling\Event;

class KernelTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_registers_scheduled_commands()
    {
        $kernel = new Kernel($this->app, $this->app->make(Dispatcher::class));
        $schedule = new Schedule;

        $kernel->exposeSchedule($schedule);

        $eventsArr = $schedule->events();

        $this->assertTrue($eventsArr[0]->command === "'/usr/bin/php8.2' 'artisan' app:clear-livewire-tmp-dir");
        $this->assertTrue($eventsArr[1]->command === "'/usr/bin/php8.2' 'artisan' app:remove-marked-as-read-database-notifications");
        $this->assertTrue($eventsArr[2]->command === "'/usr/bin/php8.2' 'artisan' model:prune");
    }

    public function test_it_executes_scheduled_command_app_clear_livewire_tmp_dir()
    {
        Artisan::call('app:clear-livewire-tmp-dir');
        $this->assertTrue(true);
    }

    public function test_it_executes_scheduled_command_app_remove_marked_as_read_database_notifications()
    {
        Artisan::call('app:remove-marked-as-read-database-notifications');
        $this->assertTrue(true);
    }

    public function test_it_executes_scheduled_command_model_prune()
    {
        Artisan::call('model:prune');
        $this->assertTrue(true);
    }
}
