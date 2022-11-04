<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
// use Illuminate\Support\Facades\Schema;
// use Illuminate\Database\Schema\Blueprint;



class ProductsImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Schema::dropIfExists('products');
        // Schema::create('products', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('product_name');
        //     $table->string('product_quantity')->default(0);
        //     $table->string('product_code')->nullable();
        //     $table->bigInteger('bar_code')->nullable();
        //     $table->bigInteger('sales_price')->default(0);
        //     $table->bigInteger('wholesale_price')->default(0);
        //     $table->json('trip_batch')->nullable();
        //     $table->integer('finished_products')->nullable();
        //     $table->integer('in_delivery')->nullable();
        //     $table->integer('spoiled_products')->nullable();
        //     $table->integer('missing_products')->nullable();
        //     $table->integer('added_by');
        //     $table->boolean('tax_exempt')->default(false);
        //     $table->timestamps();
        // });
        // Schema::drop('users');

        

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
