<?php

namespace App\Exports;

use App\Service;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductsExport implements FromCollection, WithHeadings
{   
    public function collection()
    {
        return Product::all();
    }

    public function headings(): array
    {
        return [
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
            'updated_at',
        ];
    }

}
