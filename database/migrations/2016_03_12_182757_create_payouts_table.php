<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reservation_id')->unsigned();
            $table->foreign('reservation_id')->references('id')->on('reservation');
            $table->integer('room_id')->unsigned();
            // $table->foreign('room_id')->references('room_id')->on('reservation');
            $table->enum('list_type', ['Rooms', 'Experiences'])->default('Rooms');
            $table->string('spots', 250);
            $table->string('correlation_id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->enum('user_type',['host', 'guest']);
            $table->string('account');
            $table->integer('amount');
            $table->string('currency_code',10);
            $table->foreign('currency_code')->references('code')->on('currency');
            $table->enum('status',['Completed', 'Future']);
            $table->string('penalty_id',50)->default(0);
            $table->string('penalty_amount',50)->default(0);
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
        Schema::drop('payouts');
    }
}
