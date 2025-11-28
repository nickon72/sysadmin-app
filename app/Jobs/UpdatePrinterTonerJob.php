<?php

namespace App\Jobs;

use App\Models\Printer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Services\TelegramService;



class UpdatePrinterTonerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        Log::info('=== UpdatePrinterTonerJob СТАРТ ===');

        foreach (Printer::all() as $printer) {
            try {
                $status    = $printer->getStatus();
                $tonerText = $status['toner'] ?? '—';

                // ──────── ПРАВИЛЬНЫЙ ПОРЯДОК ПРОВЕРОК (главное!) ────────
                $level = null;

                if (strpos($tonerText, 'OK') !== false || strpos($tonerText, '>80') !== false) {
                    $level = 90;
                } elseif (strpos($tonerText, 'Средне') !== false) {
                    $level = 60;
                } elseif (strpos($tonerText, 'Низкий') !== false) {
                    $level = 20;
                } elseif (strpos($tonerText, 'Критично') !== false) {
                    $level = 5;
                } elseif (strpos($tonerText, '%') !== false) {
                    $level = (int)str_replace('%', '', $tonerText);
                } elseif (is_numeric($tonerText)) {
                    $level = (int)$tonerText;
                }
                // ────────────────────────────────────────────────────────

                // Счётчик страниц — если пришёл «—», оставляем старое значение
                $pages = $status['pages'] ?? null;
                if ($pages === '—' || $pages === false || $pages === '') {
                    $pages = $printer->pages_count;
                }

                $printer->update([
                    'model'       => $status['model'] ?? $printer->model,
                    'toner_level' => $level,
                    'pages_count' => $pages,
                    'last_check'  => now(),
                ]);

                Log::info("УСПЕХ: {$printer->name} → тонер '{$tonerText}' → {$level}% | страниц: {$pages}");

            } catch (\Exception $e) {
                Log::error("ОШИБКА: {$printer->name} ({$printer->ip}) — " . $e->getMessage());
            }
        }

        Log::info('=== UpdatePrinterTonerJob ФИНИШ ===');

// ... после цикла foreach

// Уведомления только при низком тонере
$low = Printer::whereNotNull('toner_level')
              ->where('toner_level', '<', 25)
              ->get();

if ($low->count() > 0) {
    $msg = "ПРИНТЕРЫ — НИЗКИЙ ТОНЕР\n\n";
    foreach ($low as $p) {
        $msg .= "• {$p->name}\n";
        $msg .= "  Тонер: <b>{$p->toner_level}%</b>";
        if ($p->toner_level < 10) $msg .= " СРОЧНО!";
        $msg .= "\n";
    }
    TelegramService::send($msg);
}

    }// конец метода hundle

}