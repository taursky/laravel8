<?php

namespace App\Console;

use App\Model\CronModel;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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

    protected $cron;

    public function __construct(Application $app, Dispatcher $events, CronModel $cron)
    {
        parent::__construct($app, $events);
        $this->cron = $cron;
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $this->cron->currencyRate();
            //return true;
            // код, выполняемый каждый день
        })->dailyAt('00:00')->timezone('Europe/Moscow');

        $schedule->call(function () {
            $this->cron->getPriceFromYolkin();

        })->weeklyOn(1, '01:00')->timezone('Europe/Moscow');
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
