<?php

namespace App\Imports;

use App\Models\Computer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ComputersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Computer([
            'name' => $row['name'],
            'ip' => $row['ip'],
            'employee_name' => $row['employee_name'] ?? null,
            'location' => $row['location'] ?? null,
        ]);
    }
}