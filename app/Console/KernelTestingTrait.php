<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;

trait KernelTestingTrait
{
    public function exposeSchedule(Schedule $schedule)
    {
        return $this->schedule($schedule);
    }
}
