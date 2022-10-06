<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'id',
        'added_by',
        'number_users',
        'user_ids',
        'number_products',
        'number_brands',
        'products_sold',
        'products_returned',
        'products_spoiled',
        'products_missing',
        'trip_location',
        'team_lead',
        'lead_name',
        'vehicle_number',
        'driver_name',
    ];
}
