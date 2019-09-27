<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('messages');
        
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('room_id')->unsigned();
            // $table->foreign('room_id')->references('id')->on('rooms');
            $table->enum('list_type', ['Rooms', 'Experiences'])->default('Rooms');
            $table->integer('reservation_id')->unsigned()->nullable();
            $table->foreign('reservation_id')->references('id')->on('reservation');
            $table->integer('user_to')->unsigned();
            $table->foreign('user_to')->references('id')->on('users');
            $table->integer('user_from')->unsigned();
            $table->foreign('user_from')->references('id')->on('users');
            $table->text('message')->nullable();
            $table->integer('message_type')->unsigned();
            $table->foreign('message_type')->references('id')->on('message_type');
            $table->enum('read', ['0', '1']);
            $table->enum('archive', ['0', '1']);
            $table->enum('star', ['0', '1']);
            $table->integer('special_offer_id')->nullable();
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
        Schema::drop('messages');
    }
}
