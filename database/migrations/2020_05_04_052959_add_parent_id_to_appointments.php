<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParentIdToAppointments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->string("recurring")->nullable();
            $table->date("repeat_until")->nullable();
            $table->integer("parent_id")->unsigned()->nullable();
            $table->foreign("parent_id")->references('id')->on('appointments')->onDelete('cascade'); // foreign key, delete watch if order is deleted

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn("recurring");
            $table->dropColumn("parent_id");
        });
    }
}
