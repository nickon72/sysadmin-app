<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;


class Computer extends Model
{
    protected $fillable = ['name', 'ip', 'employee_name', 'location', 'notes'];

//КЭШИРОВАНИЕ СТАТУСА (опционально, +1000% скорости)
public function getStatusAttribute()
{
    return Cache::remember("computer_status_{$this->id}", 300, function () {
        return $this->ping(); // 5 минут кэш
    });
}


 public function ping()
{
    $ip = $this->ip;
    $command = 'ping -n 1 -w 1000 ' . escapeshellarg($ip) . ' 2>&1';
    exec($command, $output, $return_var);

    \Log::info('Ping output for ' . $ip . ': ' . implode(" | ", $output));

    $time = null;

    if ($return_var === 0) {
        foreach ($output as $line) {
            // 1. time<1ms → <1 → 1
            if (preg_match('/time\s*[<|=]\s*(\d+)/i', $line, $m)) {
                $time = (int)$m[1];
                if ($time === 0) $time = 1;
                break;
            }
            // 2. Время<1мс → <1 → 1
            if (preg_match('/[Вв]ремя\s*[<|=]\s*(\d+)/iu', $line, $m)) {
                $time = (int)$m[1];
                if ($time === 0) $time = 1;
                break;
            }
            // 3. Minimum = 0ms → 0 → 1
            if (preg_match('/Minimum\s*=\s*(\d+)ms/i', $line, $m)) {
                $time = (int)$m[1];
                if ($time === 0) $time = 1;
                break;
            }
            // 4. Average = 0ms → 0 → 1
            if (preg_match('/Average\s*=\s*(\d+)ms/i', $line, $m)) {
                $time = (int)$m[1];
                if ($time === 0) $time = 1;
                break;
            }
        }

        // Если не нашли — но пинг прошёл → 1 мс
        $time = $time ?? 1;

        // СТРОГОЕ ОБНОВЛЕНИЕ — УБЕДИМСЯ, ЧТО ДАННЫЕ ПОПАДАЮТ В БАЗУ
        $this->status = 'online';
        $this->ping_time = $time;
        $this->save();

        \Log::info("PING SUCCESS: {$ip} → {$time} мс");

        return ['status' => 'online', 'time' => $time];
    } else {
        $this->status = 'offline';
        $this->ping_time = null;
        $this->save();

        \Log::info("PING FAILED: {$ip}");

        return ['status' => 'offline', 'time' => null];
    }
}

}