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
            $table->string('product_code')->nullable();
            $table->bigInteger('bar_code')->nullable();
            $table->bigInteger('sales_price')->default(0);
            $table->bigInteger('wholesale_price')->default(0);
            $table->json('trip_batch')->nullable();
            $table->integer('finished_products')->nullable();
            $table->integer('in_delivery')->nullable();
            $table->integer('spoiled_products')->nullable();
            $table->integer('missing_products')->nullable();
            $table->integer('added_by');
            $table->boolean('tax_exempt')->default(false);
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
