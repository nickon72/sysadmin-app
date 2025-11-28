<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeesExport implements FromCollection, WithHeadings
{
    public function collection()
{
    return Employee::with('phones')->get()->map(function ($e) {
        return [
            'full_name'       => $e->full_name,
            'position'        => $e->position ?? '—',
            'department'      => $e->department ?? '—',
            'room'            => $e->room ?? '—',
            'email_zimbra'    => $e->email_zimbra ?? '—',
            'email_provider'  => $e->email_provider ?? '—',
            'phones'          => $e->phones->pluck('phone_number')->implode(', '),
            'notes'           => $e->notes ?? '—',
        ];
    });
}

public function headings(): array
{
    return ['ФИО', 'Должность', 'Отдел', 'Кабинет', 'Email Zimbra', 'Email провайдер', 'Телефоны', 'Примечание'];
}

}