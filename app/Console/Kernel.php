<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use DateTime;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $folderName = public_path('exports');
        // $schedule->command('inspire')->hourly();
        $schedule->call('\App\Http\Controllers\AdminController@alexaCheckBatchWithSchedule');
        // ->everyMinute();
        // ->sendOutputTo(storage_path('logs/222'.date('m-d-Y_H:i:s').'.log'));
        //Alternatively, you may use var_dump() to view the results in your terminal.
    }

    protected function shortSchedule(shortSchedule $schedule){
        $schedule->command('robot_authority_checker')->everySecond();
        // use in terminal >> php artisan make:command robot_authority_checkerCommand
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
