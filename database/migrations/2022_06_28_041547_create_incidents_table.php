<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_id')->nullable();
            $table->unsignedBigInteger('driver_in')->nullable();
            $table->unsignedBigInteger('driver_out')->nullable();
            $table->string('type', 50);             // petrol, services, others
            $table->date('date_in')->nullable();
            $table->date('date_out')->nullable();
            $table->string('km_in', 30)->nullable();
            $table->string('km_out', 30)->nullable();
            $table->float('cost', 10)->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('incidents');
    }
}
