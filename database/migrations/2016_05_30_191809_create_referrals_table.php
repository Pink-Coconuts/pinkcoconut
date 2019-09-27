<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('friend_id')->unsigned();
            $table->foreign('friend_id')->references('id')->on('users');
            $table->integer('credited_amount');
            $table->integer('friend_credited_amount');
            $table->integer('if_friend_guest_amount');
            $table->integer('if_friend_host_amount');
            $table->integer('creditable_amount');
            $table->string('currency_code',10);
            $table->foreign('currency_code')->references('code')->on('currency');
            $table->enum('status', ['Pending', 'Completed'])->default('Pending');
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
        Schema::drop('referrals');
    }
}
