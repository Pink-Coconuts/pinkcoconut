<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHostExperiencePackingListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('host_experience_packing_lists');
        Schema::create('host_experience_packing_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('host_experience_id')->unsigned();
            $table->foreign('host_experience_id')->references('id')->on('host_experiences');
            $table->text('item');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('host_experience_packing_lists');
    }
}
