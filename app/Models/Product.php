<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [ 
        'added_by',
        'product_name',
        'product_quantity',
        'stock_quantity',
        'production_price',
        'sales_price',
        'product_description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
