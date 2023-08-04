<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersNotification extends Model
{
    use HasFactory;
    protected $table='users_notification';
    protected $fillable=[
        'id',
        'token',
        'user_token',
        'notification_token',
        'status',

    ];
}
