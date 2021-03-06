<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('language');
        
        Schema::create('language', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('value', 5);
            $table->enum('is_translatable', ['1','0'])->default('1');
            $table->enum('status', ['Active','Inactive'])->default('Active');
            $table->enum('default_language', ['1','0']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('language');
    }
}
