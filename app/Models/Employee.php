<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table='employees';
    protected $fillable=[
        'token',
        'name',
        'email',
        'department',
        'contact_no',
        'dob',
        'blood_group',
        'address',
        'image',
        // 'skill',
        'user_token',
    ];
}
