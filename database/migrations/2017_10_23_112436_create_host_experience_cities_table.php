<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHostExperienceCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('host_experience_cities');
        
        Schema::create('host_experience_cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->integer('timezone');
            $table->string('currency_code', 5);
            $table->text('address');
            $table->string('latitude', 50);
            $table->string('longitude', 50);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
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
        Schema::drop('host_experience_cities');
    }
}
