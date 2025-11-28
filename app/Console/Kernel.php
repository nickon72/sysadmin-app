<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\PingComputerJob;
use App\Jobs\UpdatePrinterTonerJob;     // ← добавь эту строку
use App\Jobs\UpdateAccessPointsJob;   // ← и эту
use App\Models\Computer;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Пинг компьютеров каждые 5 минут
        $schedule->call(function () {
            foreach (Computer::all() as $computer) {
                PingComputerJob::dispatch($computer);
            }
        })->everyFiveMinutes()->name('ping-all-computers');

        // Обновление тонера принтеров каждые 5 минут
        $schedule->job(new UpdatePrinterTonerJob)->everyFiveMinutes();

        // ←←←←← НОВАЯ СТРОЧКА — ТОЧКИ ДОСТУПА КАЖДЫЕ 20 МИНУТ ←←←←←
        $schedule->job(new UpdateAccessPointsJob)->everyTwentyMinutes();
        // →→→→→ можно поменять на ->hourly() или ->everyTenMinutes(), как захочешь
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}