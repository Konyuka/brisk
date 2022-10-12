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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->integer('added_by');
            $table->integer('trip_batch');
            $table->integer('client_id')->nullable();
            $table->json('products')->nullable();
            $table->string('sale_amount');
            $table->string('payment_method')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('mpesa_ref')->nullable();
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
        Schema::dropIfExists('sales');
    }
};
