<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PropertyTypeLang extends Migration
{
    /**new
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        

        Schema::dropIfExists('property_type_lang');

           Schema::create('property_type_lang', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('property_id')->unsigned();
            $table->foreign('property_id')->references('id')->on('property_type');
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
       Schema::drop('property_type_lang');
    }
}
