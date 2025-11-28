<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramService
{
    public static function send($message)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $chat_id = env('TELEGRAM_CHAT_ID');

        if (!$token || !$chat_id) return;

        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => 'HTML',
        ]);
    }
}