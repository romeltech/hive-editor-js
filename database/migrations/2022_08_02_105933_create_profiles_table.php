<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->string('fullname')->nullable();
            $table->string('position', 150)->nullable();
            $table->string('other_email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('about_me')->nullable();
            $table->string('interest')->nullable();
            $table->string('ecode', 15)->nullable();
            $table->string('gender', 15)->nullable();
            $table->string('slug')->nullable();
            $table->string('nationality', 100)->nullable();
            $table->date('date_joining')->nullable();
            $table->date('date_birth')->nullable();
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
        Schema::dropIfExists('profiles');
    }
}
