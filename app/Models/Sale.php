<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'added_by',
        'client_id',
        'product_id',
        'sale_amount',
        'payment_status',
        'invoice_number',
        'product_quantity',
    ];
}
