<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memberships extends Model
{
    use HasFactory;
    protected $table = 'membership_plans';
    protected $fillable = [
        'plan_id',
        'plan_name',
        'plan_duration',
        'plan_amount',
        'benefits'
    ];
}
