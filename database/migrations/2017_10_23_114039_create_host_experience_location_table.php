<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHostExperienceLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('host_experience_location');
        Schema::create('host_experience_location', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('host_experience_id')->unsigned();
            $table->foreign('host_experience_id')->references('id')->on('host_experiences');
            $table->string('location_name', 50);
            $table->string('address_line_1', 255);
            $table->string('address_line_2', 255);
            $table->string('city',100);
            $table->string('state',100);
            $table->string('country',5)->nullable();
            $table->foreign('country')->references('short_name')->on('country');
            $table->string('postal_code',25);
            $table->string('latitude',50);
            $table->string('longitude',50);
            $table->text('directions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('host_experience_location');
    }
}
