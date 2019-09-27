<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsDescriptionLangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('rooms_description_lang');

        Schema::create('rooms_description_lang', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('room_id')->unsigned();
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->string('lang_code', 11);
            $table->string('name', 35);
            $table->string('summary', 500);
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
        Schema::drop('rooms_description_lang');
    }
}
