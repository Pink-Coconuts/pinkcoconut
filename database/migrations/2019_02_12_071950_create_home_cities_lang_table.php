<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeCitiesLangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('home_cities_lang');

        Schema::create('home_cities_lang', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('home_cities_id')->unsigned();
            $table->foreign('home_cities_id')->references('id')->on('home_cities');
            $table->string('name', 35);           
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
        Schema::dropIfExists('home_cities_lang');
    }
}
