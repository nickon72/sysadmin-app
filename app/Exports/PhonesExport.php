<?php

namespace App\Exports;

use App\Models\Phone;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PhonesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Phone::with('employee')->get()->map(function ($phone) {
            return [
                'phone_number' => $phone->phone_number,
                'employee'     => $phone->employee?->full_name ?? '—',
                'description'  => $phone->description ?? '—',
                'created_at'   => $phone->created_at->format('d.m.Y H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Номер телефона',
            'Сотрудник',
            'Описание',
            'Добавлен',
        ];
    }
}