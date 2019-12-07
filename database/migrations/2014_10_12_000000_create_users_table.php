<?php

use Illuminate\Support\Facades\Schema;
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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birthday')->nullable();

            $table->string('email')->unique();
            $table->string('mobile')->nullable();
            $table->string('phone')->nullable();
            $table->string('street')->nullable();
            $table->integer('zipcode')->nullable();
            $table->string('city')->nullable();

            $table->string('password');

            $table->integer('leave_days')->nullable();
            $table->integer('hours_of_work')->nullable();

            $table->string('role')->nullable();
            $table->string('employment')->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
