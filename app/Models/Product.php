<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'product_name',
        'product_quantity',
        'product_code',
        'bar_code',
        'sales_price',
        'wholesale_price',
        'trip_batch',
        'finished_products',
        'in_delivery',
        'spoiled_products',
        'missing_products',
        'added_by',
        'tax_exempt',
        'created_at',
        // 'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
