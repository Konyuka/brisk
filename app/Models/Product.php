<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [ 
        'product_name',
        'product_quantity',
        'product_code',
        'bar_code',
        'sales_price',
        'finished_products',
        'in_delivery',
        'spoiled_products',
        'added_by',
        'tax_exempt',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
