<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppliedTravelCreditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applied_travel_credit', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reservation_id')->unsigned();
            $table->foreign('reservation_id')->references('id')->on('reservation');
            $table->integer('referral_id')->unsigned();
            $table->foreign('referral_id')->references('id')->on('referrals');
            $table->integer('amount');
            $table->enum('type', ['main', 'friend'])->default('main');
            $table->string('currency_code',10);
            $table->foreign('currency_code')->references('code')->on('currency');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('applied_travel_credit');
    }
}
