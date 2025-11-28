<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_number',
        'employee_id',
        'description'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}