<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->rememberToken();
            $table->date('dob');
            $table->enum('gender',['Male', 'Female', 'Other'])->nullable();
            $table->string('live');
            $table->text('about');
            $table->string('school');
            $table->string('work');
            $table->string('timezone')->default('UTC');
            $table->string('languages');
            $table->string('email_language',10);
            $table->string('fb_id', 50)->unique()->nullable();
            $table->string('google_id', 50)->unique()->nullable();
            $table->string('linkedin_id', 50)->unique()->nullable();
            $table->string('currency_code',10)->nullable();
            $table->enum('status',['Active', 'Inactive'])->nullable();
            $table->enum('verification_status',['No','Pending','Verified', 'Resubmit'])->default('No');
            $table->timestamps();
            $table->softDeletes();
        });

        $statement = "ALTER TABLE users AUTO_INCREMENT = 10001;";

        DB::unprepared($statement);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
