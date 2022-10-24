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
            $table->boolean('trip_complete')->default(false);
            $table->integer('added_by');
            $table->integer('number_users')->default(0);
            $table->json('user_ids')->nullable();
            $table->json('products_ids')->nullable();
            $table->integer('number_products')->default(0);
            $table->integer('number_brands')->default(0);
            $table->json('products_sold')->nullable();
            $table->json('products_returned')->nullable();
            $table->json('products_spoiled')->nullable();
            $table->json('products_missing')->nullable();
            $table->json('products_summary')->nullable();
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
