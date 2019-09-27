<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHostExperienceGuestRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('host_experience_guest_requirements');
        Schema::create('host_experience_guest_requirements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('host_experience_id')->unsigned();
            $table->foreign('host_experience_id')->references('id')->on('host_experiences');
            $table->enum('includes_alcohol', ['Yes', 'No'])->default('No');
            $table->integer('minimum_age')->nullable();
            $table->enum('allowed_under_2', ['Yes', 'No'])->default('No');
            $table->text('special_certifications');
            $table->text('additional_requirements');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('host_experience_guest_requirements');
    }
}
