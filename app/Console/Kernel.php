<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('check_budgets')->everyFiveMinutes();
		$schedule->exec('/usr/bin/php8.1 /var/www/html/expense-tracker/artisan command:check_budgets')->cron('*/5 * * * *');
		$schedule->exec('/usr/bin/php8.1 /var/www/html/expense-tracker/artisan command:create_repeating_transactions')->cron('*/5 * * * *');
		$schedule->exec('/usr/bin/php8.1 /var/www/html/expense-tracker/artisan command:fetch_prices')->cron('0 22 * * *');
		$schedule->exec('/usr/bin/php8.1 /var/www/html/expense-tracker/artisan command:save_account_values')->cron('0 24 * * *');
		$schedule->exec('/usr/bin/php8.1 /var/www/html/expense-tracker/artisan command:recount_account_values')->cron('15 22 * * *');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
