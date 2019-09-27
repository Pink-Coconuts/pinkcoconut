<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBottomSliderTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('bottom_slider_translations');
        
        Schema::create('bottom_slider_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bottom_slider_id')->unsigned();
            $table->string('title', 200);   
            $table->text('description');   
            $table->string('locale',5)->index();

            $table->unique(['bottom_slider_id','locale']);
            $table->foreign('bottom_slider_id')->references('id')->on('bottom_slider')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bottom_slider_translations');
    }
}
