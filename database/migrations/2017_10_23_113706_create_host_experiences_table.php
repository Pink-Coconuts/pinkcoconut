<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHostExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('host_experiences');
        Schema::create('host_experiences', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('city');
            $table->integer('timezone');
            $table->string('currency_code', 5);
            $table->enum('hosting_standards_reviewed', ['Yes', 'No'])->default('No');
            $table->enum('experience_standards_reviewed', ['Yes', 'No'])->default('No');
            $table->string('language', 5);
            $table->integer('category')->nullable();
            $table->integer('secondary_category')->nullable();
            $table->string('title', 50);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('tagline', 100);
            $table->text('what_will_do');
            $table->text('where_will_be');
            $table->text('notes');
            $table->enum('provide_notes', ['Yes', 'No'])->nullable();
            $table->enum('need_notes', ['Yes', 'No'])->nullable();
            $table->enum('need_provides', ['Yes', 'No'])->nullable();
            $table->enum('need_packing_lists', ['Yes', 'No'])->nullable();
            $table->text('about_you');
            $table->integer('number_of_guests')->nullable();
            $table->integer('price_per_guest')->nullable();
            $table->enum('is_free_under_2', ['Yes', 'No'])->default('No');
            $table->integer('preparation_hours')->nullable();
            $table->enum('last_minute_guests', ['Yes', 'No'])->default('No');
            $table->integer('cutoff_time')->default(1);
            $table->enum('quality_standards_reviewed', ['Yes', 'No'])->default('No');
            $table->enum('local_laws_reviewed', ['Yes', 'No'])->default('No');
            $table->enum('terms_service_reviewed', ['Yes', 'No'])->default('No');
            $table->enum('is_featured', ['Yes', 'No'])->default('No');
            $table->enum('status', ['Listed', 'Unlisted'])->nullable();
            $table->enum('admin_status', ['Pending','Approved','Rejected'])->default('Pending');
            $table->timestamps();
        });
        $statement = "ALTER TABLE host_experiences AUTO_INCREMENT = 10001;";
        DB::unprepared($statement);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('host_experiences');
    }
}
