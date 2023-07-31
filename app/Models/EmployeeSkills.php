<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSkills extends Model
{
    use HasFactory;
    protected $table='employee_skills';
    protected $fillable=[
        'id',
        'token',
        'employee_token',
        'skills_token'
    ];
}
