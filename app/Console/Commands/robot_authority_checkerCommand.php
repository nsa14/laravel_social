<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class robot_authority_checkerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'robot_authority_checker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $schedule->call('\App\Http\Controllers\AdminController@alexaCheckBatchWithSchedule');
        app()->call('\App\Http\Controllers\AdminController@robot_authorityCheckSchedule');
    }
}
