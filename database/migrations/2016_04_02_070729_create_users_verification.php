<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersVerification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_verification', function (Blueprint $table) {
            $table->increments('user_id');
            $table->enum('email', ['yes', 'no'])->default('no');
            $table->enum('facebook', ['yes', 'no'])->default('no');
            $table->enum('google', ['yes', 'no'])->default('no');
            $table->enum('linkedin', ['yes', 'no'])->default('no');
            $table->enum('phone', ['yes', 'no'])->default('no');
            $table->string('fb_id', 50);
            $table->string('google_id', 50);
            $table->string('linkedin_id', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users_verification');
    }
}
