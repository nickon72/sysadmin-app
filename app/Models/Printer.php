<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;


class Printer extends Model
{
    protected $fillable = [
    'name',
    'ip',
    'model',
    'location',
    'notes',
    'toner_level',
    'pages_count',
    'last_check'
];

 public function getStatus()
{
    $ip = $this->ip;
    $community = 'public';
    $timeout = 2000000;

    $sysDescr = @snmpget($ip, $community, '.1.3.6.1.2.1.1.1.0', $timeout);
    if (!$sysDescr) {
        return [
            'model' => '—',
            'toner' => '—',
            'pages' => '—',
            'status' => 'offline',
            'error' => 'Нет ответа'
        ];
    }

    // Модель
    $model = @snmpget($ip, $community, '.1.3.6.1.2.1.25.3.2.1.3.1', $timeout);
    $model = $model ? trim(str_replace(['STRING: ', '"'], '', $model)) : '—';

    // Счётчик страниц
    $pages = @snmpget($ip, $community, '.1.3.6.1.2.1.43.10.2.1.4.1.1', $timeout);
    $pages = $pages ? trim(str_replace(['Counter32: ', 'INTEGER: '], '', $pages)) : '—';

    // ТОНЕР: СТАНДАРТНЫЙ
    $toner = '—';
    $tonerCurrent = @snmpget($ip, $community, '.1.3.6.1.2.1.43.11.1.1.9.1.1', $timeout);
    $tonerMax = @snmpget($ip, $community, '.1.3.6.1.2.1.43.11.1.1.8.1.1', $timeout);

    if ($tonerCurrent !== false && $tonerMax !== false) {
        $current = (int)trim(str_replace('INTEGER: ', '', $tonerCurrent));
        $max = (int)trim(str_replace('INTEGER: ', '', $tonerMax));
        if ($max > 0) {
            $percent = round(($current / $max) * 100);
            $toner = $percent . '%';
        }
    }

    // ЗАПАСНОЙ: KYOCERA
    if ($toner === '—') {
        $kyoceraCurrent = @snmpget($ip, $community, '.1.3.6.1.4.1.1347.43.10.1.1.12.1.1', $timeout);
        if ($kyoceraCurrent !== false) {
            $val = (int)trim(str_replace('INTEGER: ', '', $kyoceraCurrent));
            if ($val > 12000) $toner = 'OK (>80%)';
            elseif ($val > 8000) $toner = 'Средне';
            elseif ($val > 4000) $toner = 'Низкий';
            else $toner = 'Критично';
        }
    }

    return [
        'model' => $model,
        'toner' => $toner,
        'pages' => $pages,
        'status' => 'online'
    ];
}




}