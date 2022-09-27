<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'added_by',
        'number_users',
        'number_products',
        'products_sold',
        'products_returned',
        'products_spoiled',
        'trip_location',
        'team_lead',
        'vehicle_number',
        'driver_name',
    ];
}
