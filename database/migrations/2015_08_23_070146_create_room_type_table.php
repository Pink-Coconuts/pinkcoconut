<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('room_type');
        
        Schema::create('room_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 25);
            $table->text('description');
            $table->string('icon', 50);
            $table->enum('is_shared', ['Yes', 'No'])->default('No');
            $table->enum('status', ['Active','Inactive'])->default('Active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('room_type');
    }
}
