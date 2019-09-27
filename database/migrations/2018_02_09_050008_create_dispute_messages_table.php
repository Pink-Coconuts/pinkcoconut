<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisputeMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('dispute_messages');

        Schema::create('dispute_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dispute_id')->unsigned();
            $table->foreign('dispute_id')->references('id')->on('disputes');
            $table->enum('message_by', ['Admin', 'Guest', 'Host'])->default('Host');
            $table->enum('message_for', ['Admin', 'Guest', 'Host'])->default('Guest');
            $table->integer('user_from');
            $table->integer('user_to');
            $table->text('message');
            $table->integer('amount')->unsigned()->nullable();
            $table->string('currency_code',10);
            $table->foreign('currency_code')->references('code')->on('currency');
            $table->enum('read', ['0', '1'])->default('0');
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
        Schema::drop('dispute_messages');
    }
}
