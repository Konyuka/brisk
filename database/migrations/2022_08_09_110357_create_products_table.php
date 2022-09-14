<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('product_quantity')->default(0);
            $table->integer('product_code')->default(0);
            $table->integer('bar_code')->default(0);
            $table->integer('sales_price')->default(0);
            $table->integer('finished_products')->default(0);
            $table->integer('in_delivery')->default(0);
            $table->integer('spoiled_products')->default(0);
            $table->string('added_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
