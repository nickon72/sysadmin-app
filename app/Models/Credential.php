<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credential extends Model
{
    protected $fillable = [
    'service_name', 'login', 'password', 'password_encrypted',
    'connection_method', 'description', 'encrypted'
];
}