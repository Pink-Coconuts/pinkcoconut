<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmenitiesLangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::dropIfExists('amenities_lang');

           Schema::create('amenities_lang', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('amenities_id')->unsigned();
            $table->foreign('amenities_id')->references('id')->on('amenities');            
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
        Schema::drop('amenities_lang');
    }
}
