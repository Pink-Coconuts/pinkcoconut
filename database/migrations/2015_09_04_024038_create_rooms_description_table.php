<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsDescriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('rooms_description');

        Schema::create('rooms_description', function (Blueprint $table) {
            $table->integer('room_id')->unique()->unsigned();
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->text('space');
            $table->text('access');
            $table->text('interaction');
            $table->text('notes');
            $table->text('house_rules');
            $table->text('neighborhood_overview');
            $table->text('transit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rooms_description');
    }
}
