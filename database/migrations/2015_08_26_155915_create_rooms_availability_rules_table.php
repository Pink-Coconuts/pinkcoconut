<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsAvailabilityRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('rooms_availability_rules');

        Schema::create('rooms_availability_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('room_id')->unsigned();
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->enum('type', ['custom', 'month'])->default('custom');
            $table->integer('minimum_stay')->nullable();
            $table->integer('maximum_stay')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rooms_availability_rules');
    }
}
