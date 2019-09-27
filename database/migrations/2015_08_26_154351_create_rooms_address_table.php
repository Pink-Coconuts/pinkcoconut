<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('rooms_address');
        
        Schema::create('rooms_address', function (Blueprint $table) {
            $table->integer('room_id')->unique()->unsigned();
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->string('address_line_1');
            $table->string('address_line_2');
            $table->string('city',100);
            $table->string('state',100);
            $table->string('country',5);
            $table->foreign('country')->references('short_name')->on('country');
            $table->string('postal_code',25);
            $table->string('latitude',50);
            $table->string('longitude',50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rooms_address');
    }
}
