<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;

class CreateCalendarTables extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('calendar_operations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();

			$table->string('title');
			$table->timestamp('start_at')->nullable();
			$table->timestamp('end_at')->nullable();
			$table->enum('type', ['PvE', 'PvP', 'Other'])->nullable();
			$table->string('importance')->nullable();
			$table->string('description')->nullable();
			$table->string('staging')->nullable();
			$table->string('fc')->nullable();
			$table->boolean('is_cancelled')->default(false);
			$table->nullableTimestamps();

			$table->foreign('user_id')
				->references('id')
				->on('users')
				->onDelete('cascade');
		});

		Schema::create('calendar_attendees', function (Blueprint $table) {
			$table->increments('id');
			
			$table->integer('operation_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->enum('status', ['yes', 'no', 'maybe']);
			$table->string('comment')->nullable();
			$table->nullableTimestamps();
			
			$table->foreign('operation_id')
				->references('id')
				->on('calendar_operations')
				->onDelete('cascade');
			$table->foreign('user_id')
				->references('id')
				->on('users')
				->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('calendar_attendees');
		Schema::drop('calendar_operations');
	}
}