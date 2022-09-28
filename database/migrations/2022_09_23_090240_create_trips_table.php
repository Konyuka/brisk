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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->integer('added_by');
            $table->string('number_users')->default(0);
            $table->string('number_products')->default(0);
            $table->string('number_brands')->default(0);
            $table->string('products_sold')->default(0);
            $table->string('products_returned')->default(0);
            $table->string('products_spoiled')->default(0);
            $table->string('trip_location')->nullable();
            $table->integer('team_lead')->nullable();
            $table->string('lead_name')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('driver_name')->nullable();
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
        Schema::dropIfExists('trips');
    }
};
