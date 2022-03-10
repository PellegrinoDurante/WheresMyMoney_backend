<?php

namespace App\Console;

use App\Services\TriggerService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     * @throws BindingResolutionException
     */
    protected function schedule(Schedule $schedule)
    {
        /** @var TriggerService $triggerService */
        $triggerService = $this->app->make(TriggerService::class);

        // Get recurring expenses with trigger to be scheduled
        $recurringExpenses = $triggerService->getExpenseWithSchedulingTriggers();

        // Register a command for each recurring expense with a scheduling trigger
        $recurringExpenses->each(function ($recurringExpense) use ($schedule) {
            // TODO: schedule a job for triggers check
        });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
