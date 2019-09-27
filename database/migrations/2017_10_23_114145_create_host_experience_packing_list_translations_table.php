<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHostExperiencePackingListTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('host_experience_packing_list_translations');
        Schema::create('host_experience_packing_list_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('host_experience_translation_id')->unsigned();
            $table->foreign('host_experience_translation_id', 'heplt_he_translation_id_foreign')->references('id')->on('host_experience_translations');
            $table->integer('host_experience_packing_list_id')->unsigned();
            $table->foreign('host_experience_packing_list_id', 'heplt_he_packing_list_id_foreign')->references('id')->on('host_experience_packing_lists');
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
        Schema::drop('host_experience_packing_list_translations');
    }
}
