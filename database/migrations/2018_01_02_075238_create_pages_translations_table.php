<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pages_id')->unsigned();
            $table->string('name');   
            $table->longText('content');   
            $table->string('locale',5)->index();

            $table->unique(['pages_id','locale']);
            $table->foreign('pages_id')->references('id')->on('pages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pages_translations');
    }
}
