<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timers', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->bigInteger('itemID')->unsigned();
            $table->integer('structureType');
            $table->integer('structureStatus');
            $table->integer('bashed');
            $table->integer('outcome');
            $table->integer('timerType');
            $table->integer('type');
            $table->dateTime('timeExiting');
            $table->integer('user_id');
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
        Schema::drop('timers');
    }
}
