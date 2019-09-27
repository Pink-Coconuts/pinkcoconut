<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOurCommunityBannersTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('our_community_banners_translations');
        
        Schema::create('our_community_banners_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('our_community_banners_id')->unsigned();
            $table->string('title', 200);   
            $table->text('description');   
            $table->string('locale',5)->index();

            $table->unique(['our_community_banners_id','locale'], 'ocb_unique');
            $table->foreign('our_community_banners_id', 'our_community_banners_id_foriegn')->references('id')->on('our_community_banners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('our_community_banners_translations');
    }
}
