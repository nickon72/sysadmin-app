<?php

namespace App\Jobs;

use App\Models\AccessPoint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateAccessPointsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $baseUrl = 'https://192.168.35.11:8043';
    protected $siteId  = '66fe472e3e43f5717652a36c';

  public function handle()
{
    $client = Http::withOptions(['verify' => false])
                  ->withCookies([], '192.168.35.11');

    // 1. Логин
    $login = $client->withHeader('Content-Type', 'application/json')
                    ->post("{$this->baseUrl}/api/v2/login", [
                        'username' => 'admin',
                        'password' => 'ServiceMode',
                    ]);

    $token = $login->json('result.token');

    // 2. Активация
    $client->withHeader('TP-TOKEN', $token)->get("{$this->baseUrl}/");

    // 3. currentUser
    $client->withHeader('TP-TOKEN', $token)->get("{$this->baseUrl}/api/v2/currentUser");

    // ←←← ЭТОТ POST — КЛЮЧ КО ВСЕМУ ←←←
    $client->withHeaders([
        'TP-TOKEN'     => $token,
        'Content-Type' => 'application/json',
        'Referer'      => "{$this->baseUrl}/",
    ])->post("{$this->baseUrl}/{$this->siteId}/api/v2/sites/current");

    // 4. Теперь API работает!
    $response = $client->withHeaders([
        'TP-TOKEN' => $token,
        'Referer'  => "{$this->baseUrl}/",
    ])->get("{$this->baseUrl}/{$this->siteId}/api/v2/devices", [
        'type' => 'ap',
        'currentPageSize' => 100,
    ]);

    dd([
        'final_status' => $response->status(),
        'content_type' => $response->header('Content-Type'),
        'body_start'   => substr($response->body(), 0, 1000),
        'json_preview'   => $response->json(),
    ]);
}

}