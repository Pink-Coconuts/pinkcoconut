<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('rooms_price');

        Schema::create('rooms_price', function (Blueprint $table) {
            $table->integer('room_id')->unique()->unsigned();
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->integer('night');
            $table->integer('cleaning');
            $table->integer('additional_guest');
            $table->tinyInteger('guests');
            $table->integer('security');
            $table->integer('weekend');
            $table->integer('minimum_stay')->nullable();
            $table->integer('maximum_stay')->nullable();
            $table->string('currency_code',10);
            $table->foreign('currency_code')->references('code')->on('currency');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rooms_price');
    }
}
