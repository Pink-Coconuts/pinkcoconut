<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBedTypeLangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::dropIfExists('bed_type_lang');

           Schema::create('bed_type_lang', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bed_type_id')->unsigned();
            $table->foreign('bed_type_id')->references('id')->on('bed_type');
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
         Schema::drop('bed_type_lang');
    }
}
