<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomTypeLangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('room_type_lang');

           Schema::create('room_type_lang', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('room_type_id')->unsigned();
            $table->foreign('room_type_id')->references('id')->on('room_type');
            $table->string('name', 35);
            $table->string('description', 2000);
            $table->string('lang_code',5);
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
         Schema::drop('room_type_lang');
    }
}
