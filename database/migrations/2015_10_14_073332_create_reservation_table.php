<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('reservation');

        Schema::create('reservation', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code',10);
            $table->integer('room_id')->unsigned();
            // $table->foreign('room_id')->references('id')->on('rooms');
            $table->enum('list_type', ['Rooms', 'Experiences'])->default('Rooms');
            $table->integer('host_id')->unsigned();
            $table->foreign('host_id')->references('id')->on('users');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->date('checkin');
            $table->date('checkout');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('number_of_guests');
            $table->integer('nights');
            $table->integer('per_night');
            $table->integer('subtotal');
            $table->integer('cleaning');
            $table->integer('additional_guest');
            $table->integer('security');
            $table->integer('service');
            $table->integer('host_fee');            
            $table->enum('host_penalty', ['0', '1'])->default('0');
            $table->integer('total');
            $table->string('coupon_code',50);
            $table->integer('coupon_amount');
            $table->integer('base_per_night');
            $table->enum('length_of_stay_type', ['weekly', 'monthly', 'custom'])->nullable();
            $table->integer('length_of_stay_discount');
            $table->integer('length_of_stay_discount_price');
            $table->enum('booked_period_type', ['early_bird', 'last_min'])->nullable();
            $table->integer('booked_period_discount');
            $table->integer('booked_period_discount_price');
            $table->string('currency_code',10);
            $table->foreign('currency_code')->references('code')->on('currency');
            $table->string('paypal_currency',10)->nullable();
            $table->string('transaction_id',50);
            $table->enum('paymode', ['PayPal', 'Credit Card'])->nullable();
            $table->enum('cancellation', ['Flexible', 'Moderate', 'Strict'])->default('Flexible');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('postal_code',20);
            $table->string('country',5)->nullable();
            $table->foreign('country')->references('short_name')->on('country');
            $table->enum('status', ['Pending', 'Accepted', 'Declined', 'Expired', 'Checkin', 'Checkout', 'Completed', 'Cancelled','Pre-Accepted','Pre-Approved'])->nullable();
            $table->enum('type', ['contact', 'reservation'])->nullable();
            $table->text('friends_email');
            $table->enum('cancelled_by', ['Guest', 'Host'])->nullable();
            $table->string('cancelled_reason',500);
            $table->text('decline_reason');
            $table->integer('host_remainder_email_sent');
            $table->integer('special_offer_id');              
            $table->timestamp('accepted_at');
            $table->timestamp('expired_at');
            $table->timestamp('declined_at');
            $table->timestamp('cancelled_at');
            $table->timestamps();
            $table->string('date_check',5);
            $table->string('pphone_number',20);
            $table->integer('airportfee');
            $table->text('ngo_list');
            $table->integer('ngo_amount');
        });

        $statement = "ALTER TABLE reservation AUTO_INCREMENT = 10001;";

        DB::unprepared($statement);
    }

    


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reservation');
    }
}
