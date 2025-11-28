<?php

namespace App\Jobs;

use App\Models\Computer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PingComputerJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    protected $computer;

    public function __construct(Computer $computer)
    {
        $this->computer = $computer;
    }

    public function handle()
    {
        $this->computer->ping(); // твой метод из модели
    }
}