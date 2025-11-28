<?php

namespace App\Exports;

use App\Models\Credential;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CredentialsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Credential::select(
            'service_name',
            'login',
            'connection_method',
            'description'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Сервис',
            'Логин',
            'Метод подключения',
            'Описание',
        ];
    }
}