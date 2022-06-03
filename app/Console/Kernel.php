<?php

namespace App\Console;

use App\Console\Commands\RemindUserToPayRemainBookingFeeCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        RemindUserToPayRemainBookingFeeCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command(RemindUserToPayRemainBookingFeeCommand::class)->dailyAt('09:00');
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
