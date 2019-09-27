<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDateformatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('dateformats');
        
        Schema::create('dateformats', function (Blueprint $table) {
            $table->increments('id');
            $table->string('display_format',255);
            $table->string('display_format1',255);
            $table->string('php_format',255);
            $table->string('uidatepicker_format',255);
            $table->string('daterangepicker_format',255);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
        });    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::drop('dateformats');
    }
}
