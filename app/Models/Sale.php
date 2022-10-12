<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'added_by',
        'trip_batch',
        'client_id',
        'products',
        'sale_amount',
        'payment_method',
        'invoice_number',
        'mpesa_ref',
    ];
}
