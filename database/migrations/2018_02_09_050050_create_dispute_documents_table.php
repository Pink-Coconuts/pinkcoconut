<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisputeDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('dispute_documents');

        Schema::create('dispute_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dispute_id')->unsigned();
            $table->foreign('dispute_id')->references('id')->on('disputes');
            $table->string('file', 100);
            $table->integer('uploaded_by')->unsigned();
            $table->foreign('uploaded_by')->references('id')->on('users');
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
        Schema::drop('dispute_documents');
    }
}
