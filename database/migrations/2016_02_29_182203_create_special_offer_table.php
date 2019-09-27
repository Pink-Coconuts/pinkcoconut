<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecialOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_offer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reservation_id')->unsigned();
            $table->foreign('reservation_id')->references('id')->on('reservation');
            $table->integer('room_id')->unsigned();
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->integer('user_id');
            $table->date('checkin');
            $table->date('checkout');
            $table->tinyInteger('number_of_guests');
            $table->integer('price');
            $table->string('currency_code',10);
            $table->foreign('currency_code')->references('code')->on('currency');
            $table->enum('type',['pre-approval', 'special_offer']);
            $table->timestamp('created_at');
        });

        $statement = "ALTER TABLE special_offer AUTO_INCREMENT = 10001;";

        DB::unprepared($statement);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('special_offer');
    }
}
