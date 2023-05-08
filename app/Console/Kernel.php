<?php

namespace App\Console;

use App\Models\Ticket;
use App\Models\Tracking;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // pending ticket
        $tickets = Ticket::where('status', 4)->get();
        foreach ($tickets as $ticket) {
            $ticket->status = 5;
            $tracking = new Tracking;
            $tracking->status   = 'Ticket Postponed';
            $tracking->note     = 'Pending automatically by system';

            $schedule->call(function () use ($ticket, $tracking) {
                $ticket->save();
                $ticket->trackings()->save($tracking);
            })->everyMinute();
        }

        // continue ticket
        $tickets = Ticket::where('status', 5)->whereHas('trackings', function ($q) {
            $q->where('note', 'Pending automatically by system');
        })->get();

        foreach ($tickets as $ticket) {
            $ticket->status = 4;
            $tracking = new Tracking;
            $tracking->status   = 'Ticket Continued';
            $tracking->note     = 'Continue automatically by system';

            $schedule->call(function () use ($ticket, $tracking) {
                $ticket->save();
                $ticket->trackings()->save($tracking);
            })->weekdays()->dailyAt('7:30')->timezone('Asia/Jakarta');
        }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
