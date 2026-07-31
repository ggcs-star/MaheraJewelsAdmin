<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
   protected function schedule(Schedule $schedule)
{
    $schedule->command('stock:check-low')->everyMinute();
        $schedule->command('instagram:sync-reels')->everyThirtyMinutes();

        // Instagram Token Refresh
        $schedule->command('instagram:refresh-token')->daily();

    $schedule->command('app:auto-confirm-orders')
             ->everyMinute();
                 $schedule->command('app:sync-amazon-orders')->everyFiveMinutes();

        $schedule->command('app:sync-amazon-orders')->daily();
        $schedule->command('app:sync-amazon-products')->daily();
        $schedule->command('app:sync-amazon-inventory')->daily();
}
    
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
