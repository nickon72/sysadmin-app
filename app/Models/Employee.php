<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'department',
        'position',
        'room',
        'notes',
        'email_zimbra',
        'email_provider'
    ];

    public function phones()
    {
        return $this->hasMany(Phone::class);
    }
}