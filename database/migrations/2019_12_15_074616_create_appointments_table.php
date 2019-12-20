<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer("type")->unsigned();

            $table->string("description")->nullable();
            $table->text("note")->nullable();

            $table->timestamp("date_from")->nullable();
            $table->timestamp("date_to")->nullable();

            $table->integer("location_id")->unsigned()->nullable();
            $table->foreign("location_id")->references('id')->on('locations');

            $table->integer("user_id")->unsigned()->nullable();
            $table->foreign("user_id")->references('id')->on('users');

            $table->integer("created_by")->unsigned()->nullable();
            $table->foreign("created_by")->references('id')->on('users');

            $table->integer("edited_by")->unsigned()->nullable();
            $table->foreign("edited_by")->references('id')->on('users');

            $table->integer("shift_request_id")->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
