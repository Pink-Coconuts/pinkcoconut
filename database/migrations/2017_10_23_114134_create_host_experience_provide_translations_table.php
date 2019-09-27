<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHostExperienceProvideTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('host_experience_provide_translations');
        Schema::create('host_experience_provide_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('host_experience_translation_id')->unsigned();
            $table->foreign('host_experience_translation_id', 'hept_he_translation_id_foreign')->references('id')->on('host_experience_translations');
            $table->integer('host_experience_provide_id')->unsigned();
            $table->foreign('host_experience_provide_id', 'hept_he_provide_id_foreign')->references('id')->on('host_experience_provides');
            $table->string('name', 25);
            $table->text('additional_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('host_experience_provide_translations');
    }
}
