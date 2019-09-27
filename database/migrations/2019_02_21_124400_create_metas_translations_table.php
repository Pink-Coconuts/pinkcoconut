<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetasTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metas_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('metas_id')->unsigned();
            $table->string('title', 200);   
            $table->text('description');   
            $table->text('keywords');   
            $table->string('locale',5)->index();

            $table->unique(['metas_id','locale'], 'ocb_unique');
            $table->foreign('metas_id', 'metas_id_foriegn')->references('id')->on('metas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metas_translations');
    }
}
