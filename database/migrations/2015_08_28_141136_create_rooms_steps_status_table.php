<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsStepsStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('rooms_steps_status');

        Schema::create('rooms_steps_status', function (Blueprint $table) {
            $table->integer('room_id')->unique()->unsigned();
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->enum('basics',['0','1'])->default('0');
            $table->enum('description',['0','1'])->default('0');
            $table->enum('location',['0','1'])->default('0');
            $table->enum('photos',['0','1'])->default('0');
            $table->enum('pricing',['0','1'])->default('0');
            $table->enum('calendar',['0','1'])->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rooms_steps_status');
    }
}
