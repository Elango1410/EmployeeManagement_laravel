<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Legals extends Model
{
    use HasFactory;
    protected $table='legals';
    protected $fillable=[
        'token',
        'content',
        'content_type'
    ];
}
