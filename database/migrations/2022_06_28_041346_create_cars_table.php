<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('plate_no', 50);
            $table->string('title');
            $table->string('year',4);               // should not be edited once created
            $table->string('color',15);
            $table->string('chassis_no', 100);      // should not be edited once created
            $table->string('registration_expiry')->nullable();
            $table->string('insurance_expiry')->nullable();
            $table->string('km')->default(0);                   // should not be edited once created
            $table->string('status', 10);
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
        Schema::dropIfExists('cars');
    }
}
