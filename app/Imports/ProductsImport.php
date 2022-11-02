<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;


class ProductsImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Product([
            'id'     => $row[0],
            'product_name'    => $row[1],
            'product_quantity'    => $row[2],
            'product_code'    => $row[3],
            'bar_code'    => $row[4],
            'sales_price'    => $row[5],
            'wholesale_price'    => $row[6],
            'trip_batch'    => $row[7],
            'finished_products'    => $row[8],
            'in_delivery'    => $row[9],
            'spoiled_products'    => $row[10],
            'missing_products'    => $row[11],
            'added_by'    => $row[12],
            'tax_exempt'    => $row[13],
            'created_at'    => $row[14],
            // 'updated_at'    => $row[15],
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }
}
