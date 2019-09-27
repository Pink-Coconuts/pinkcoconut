<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('rooms');

        Schema::create('rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('name', 35);
            $table->string('sub_name');
            $table->string('summary', 500);
            $table->string('check_in_time')->default('Flexible');
            $table->string('check_out_time')->default('Flexible');
            $table->text('booking_message');
            $table->integer('property_type')->unsigned();
            $table->foreign('property_type')->references('id')->on('property_type');
            $table->integer('room_type')->unsigned();
            $table->foreign('room_type')->references('id')->on('room_type');
            $table->enum('is_shared', ['Yes', 'No'])->default('No');
            $table->tinyInteger('accommodates')->nullable();
            $table->tinyInteger('bedrooms')->nullable();
            $table->tinyInteger('beds')->nullable();
            $table->integer('bed_type')->unsigned()->nullable();
            $table->foreign('bed_type')->references('id')->on('bed_type');
            $table->float('bathrooms',2,1)->nullable();
            $table->string('amenities')->nullable();
            $table->string('video');
            $table->enum('calendar_type', ['Always', 'Sometimes', 'Onetime'])->nullable();
            $table->enum('booking_type', ['request_to_book', 'instant_book'])->nullable();
            $table->enum('cancel_policy', ['Flexible', 'Moderate', 'Strict'])->default('Flexible');
            $table->enum('popular',['Yes', 'No'])->default('No');
            $table->enum('started',['Yes', 'No'])->default('No');
            $table->enum('recommended',['Yes', 'No'])->default('No');
            $table->integer('views_count');
            $table->enum('status',['Listed', 'Unlisted'])->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        $statement = "ALTER TABLE rooms AUTO_INCREMENT = 10001;";

        DB::unprepared($statement);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rooms');
    }
}
