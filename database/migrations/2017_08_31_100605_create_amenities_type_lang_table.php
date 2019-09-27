<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmenitiesTypeLangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('amenities_type_lang');

           Schema::create('amenities_type_lang', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('amenities_type_id')->unsigned();
            $table->foreign('amenities_type_id')->references('id')->on('amenities_type');
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
        Schema::drop('amenities_type_lang');
    }
}
