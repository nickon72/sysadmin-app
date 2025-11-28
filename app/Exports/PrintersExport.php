<?php

namespace App\Exports;

use App\Models\Printer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PrintersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Printer::all()->map(function ($p) {
            $status = $p->getStatus();
            return [
                'name' => $p->name,
                'ip' => $p->ip,
                'model' => $status['model'],
                'toner' => $status['toner'],
                'pages' => $status['pages'],
                'status' => $status['status'],
                'location' => $p->location ?? '—',
                'notes' => $p->notes ?? '—',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Название',
            'IP',
            'Модель',
            'Тонер',
            'Счётчик',
            'Статус',
            'Локация',
            'Примечания',
        ];
    }
}