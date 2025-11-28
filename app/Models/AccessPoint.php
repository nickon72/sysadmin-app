<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class AccessPoint extends Model
{
    protected $fillable = [
        'name',
        'mac_address',
        'ip',
        'model',
        'firmware',
        'uptime_seconds',
        'status',
        'last_check',
    ];

    // ←←←←← ЭТО ДОБАВЬ! ←←←←←
    protected $casts = [
        'last_check' => 'datetime',
    ];
    // →→→→→

    public function getUptimeAttribute()
    {
        if (!$this->uptime_seconds) return '—';
        $d = floor($this->uptime_seconds / 86400);
        $h = floor(($this->uptime_seconds % 86400) / 3600);
        $m = floor(($this->uptime_seconds % 3600) / 60);
        $s = $this->uptime_seconds % 60;

        if ($d > 0) return "{$d}d {$h}h {$m}m";
        if ($h > 0) return "{$h}h {$m}m {$s}s";
        return "{$m}m {$s}s";
    }

    public function isOnline(): bool
    {
        return $this->status === 'connected';
    }
}