<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisputesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('disputes');

        Schema::create('disputes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reservation_id')->unsigned();
            $table->foreign('reservation_id')->references('id')->on('reservation');
            $table->enum('dispute_by', ['Host', 'Guest'])->default('Host');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('dispute_user_id')->unsigned();
            $table->foreign('dispute_user_id')->references('id')->on('users');
            $table->text('subject');
            $table->integer('amount')->unsigned();
            $table->integer('final_dispute_amount')->unsigned()->nullable();
            $table->string('currency_code',10);
            $table->foreign('currency_code')->references('code')->on('currency');

            $table->enum('payment_status', ['Pending', 'Completed'])->nullable();
            $table->enum('paymode', ['PayPal', 'Credit Card'])->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('postal_code',20)->nullable();
            $table->string('country',5);
            $table->string('transaction_id',50)->nullable();

            $table->enum('status', ['Open','Processing', 'Closed'])->default('Open');
            $table->enum('admin_status', ['Open', 'Confirmed'])->default('Open');
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
        Schema::drop('disputes');
    }
}
