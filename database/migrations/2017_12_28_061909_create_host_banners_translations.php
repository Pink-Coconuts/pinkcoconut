<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHostBannersTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('host_banners_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('host_banners_id')->unsigned();
            $table->string('title');
            $table->text('description');
            $table->string('link_title');
            $table->string('locale',5)->index();

            $table->unique(['host_banners_id','locale']);
            $table->foreign('host_banners_id')->references('id')->on('host_banners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('host_banners_translations');
    }
}
