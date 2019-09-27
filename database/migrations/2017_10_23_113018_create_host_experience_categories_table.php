<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHostExperienceCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('host_experience_categories');

        Schema::create('host_experience_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->enum('is_featured', ['Yes', 'No'])->default('No');
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
        Schema::drop('host_experience_categories');
    }
}
