<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashlessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashlesses', function (Blueprint $table) {
            $table->id();
            $table->string('ecode',20)->nullable();
            $table->string('fullname')->nullable();
            $table->string('company')->nullable();
            $table->string('email')->nullable();
            $table->float('amount')->nullable();
            $table->string('date_closing')->nullable();
            $table->string('type')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
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
        Schema::dropIfExists('cashlesses');
    }
}
