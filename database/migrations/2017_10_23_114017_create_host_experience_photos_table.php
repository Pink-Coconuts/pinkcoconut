<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHostExperiencePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('host_experience_photos');
        Schema::create('host_experience_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('host_experience_id')->unsigned();
            $table->foreign('host_experience_id')->references('id')->on('host_experiences');
            $table->string('name', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('host_experience_photos');
    }
}
