<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSavedWishlistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saved_wishlists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('room_id')->unsigned();
            // $table->foreign('room_id')->references('id')->on('rooms');
            $table->enum('list_type', ['Rooms', 'Experiences'])->default('Rooms');
            $table->integer('wishlist_id')->unsigned();
            $table->foreign('wishlist_id')->references('id')->on('wishlists');
            $table->text('note');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('saved_wishlists');
    }
}
