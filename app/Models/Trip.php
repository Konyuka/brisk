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
        'number_brands',
        'products_sold',
        'products_returned',
        'products_spoiled',
        'trip_location',
        'team_lead',
        'lead_name',
        'vehicle_number',
        'driver_name',
    ];
}
