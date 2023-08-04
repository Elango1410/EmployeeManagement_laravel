<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersTab extends Model
{
    use HasFactory;


    protected $table='users_tab';
    protected $fillable=[
        'id',
        'token',
        'name',
        'mobile_number',
        'email',
        'image',
        'type',
        'device_type',
        'device_toke',
        'status'
    ];
}
