<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reservation_id')->unsigned();
            $table->foreign('reservation_id')->references('id')->on('reservation');
            $table->integer('room_id')->unsigned();
            // $table->foreign('room_id')->references('id')->on('rooms');
            $table->enum('list_type', ['Rooms', 'Experiences'])->default('Rooms');
            $table->integer('user_from')->unsigned();
            $table->foreign('user_from')->references('id')->on('users');
            $table->integer('user_to')->unsigned();
            $table->foreign('user_to')->references('id')->on('users');
            $table->enum('review_by', ['guest', 'host']);
            $table->text('comments');
            $table->text('private_feedback');
            $table->text('love_comments');
            $table->text('improve_comments');
            $table->integer('rating');
            $table->integer('accuracy');
            $table->text('accuracy_comments');
            $table->integer('cleanliness');
            $table->text('cleanliness_comments');
            $table->integer('checkin');
            $table->text('checkin_comments');
            $table->integer('amenities');
            $table->text('amenities_comments');
            $table->integer('communication');
            $table->text('communication_comments');
            $table->integer('location');
            $table->text('location_comments');
            $table->integer('value');
            $table->text('value_comments');
            $table->integer('respect_house_rules');
            $table->integer('recommend');
            $table->string('pride_comments',25);
            $table->timestamps();
        });
    }

    //ALTER TABLE `reviews` ADD `pride_comments` VARCHAR(25) NOT NULL AFTER `updated_at`;

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reviews');
    }
}
