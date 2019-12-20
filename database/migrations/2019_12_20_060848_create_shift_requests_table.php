<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShiftRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer("type")->unsigned(); // free / leave_days
            $table->integer("status")->unsigned()->default(0); // 0, 1, 2

            $table->date("date_from")->nullable();
            $table->date("date_to")->nullable();

            $table->text("note")->nullable();

            $table->integer("user_id")->unsigned()->nullable();
            $table->foreign("user_id")->references('id')->on('users');

            $table->integer("edited_by")->unsigned()->nullable();
            $table->foreign("edited_by")->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shift_requests');
    }
}
