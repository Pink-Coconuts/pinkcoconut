<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHostExperienceCalendarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('host_experience_calendar', function (Blueprint $table) {
            $table->increments('id');            
            $table->integer('host_experience_id')->unsigned();
            $table->foreign('host_experience_id')->references('id')->on('host_experiences');
            $table->date('date');
            $table->integer('price');
            $table->integer('spots_booked');
            $table->string('spots', 250);
            $table->enum('source', ['Calendar', 'Reservation'])->default('Calendar');
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
        Schema::drop('host_experience_calendar');
    }
}
