<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHostExperienceProvidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('host_experience_provides');
        Schema::create('host_experience_provides', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('host_experience_id')->unsigned();
            $table->foreign('host_experience_id')->references('id')->on('host_experiences');
            $table->integer('host_experience_provide_item_id');
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
        Schema::drop('host_experience_provides');
    }
}
