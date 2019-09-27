<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('room_id')->unsigned();
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->date('date');
            $table->integer('price');
            $table->text('notes')->nullable();
            $table->integer('spots_booked')->unsigned();
            $table->enum('source', ['Calendar', 'Reservation','Sync'])->default('Reservation');
            $table->enum('is_shared', ['Yes', 'No'])->default('No');
            $table->enum('status',['Available', 'Not available'])->nullable();
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
        Schema::drop('calendar');
    }
}
