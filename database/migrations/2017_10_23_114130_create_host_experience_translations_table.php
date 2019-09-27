<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHostExperienceTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('host_experience_translations');
        Schema::create('host_experience_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('host_experience_id')->unsigned();
            $table->foreign('host_experience_id')->references('id')->on('host_experiences');
            $table->string('language', 5);
            $table->enum('language_terms_reviewed', ['Yes', 'No'])->default('No');
            $table->string('title', 50);
            $table->string('tagline', 100);
            $table->text('what_will_do');
            $table->text('where_will_be');
            $table->text('notes');
            $table->text('about_you');
            $table->string('location_name', 50);
            $table->text('special_certifications');
            $table->text('additional_requirements');
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
        Schema::drop('host_experience_translations');
    }
}
