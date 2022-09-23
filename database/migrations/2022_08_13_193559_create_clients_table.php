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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('added_by');
            $table->string('client_name');
            $table->string('client_email')->default(0);
            $table->integer('client_contact')->default(0);
            $table->string('client_kra')->default(0);
            $table->string('client_address')->nullable();
            $table->integer('client_balance')->nullable();
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
        Schema::dropIfExists('clients');
    }
};
