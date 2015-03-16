<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShiftsSystem extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('task_types', function(Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->string("name",64);
			$table->string("short_description",160);
			$table->text("long_description")->default("");
			$table->tinyInteger("day_of_week")->unsigned();
			$table->string("repeat_days",2);
			$table->time("time_start")->nullable();
			$table->time("time_stop")->nullable();
			$table->date("published_start")->nullable();
			$table->date("published_stop")->nullable();
			$table->integer("member_group_id")->unsigned();
			$table->boolean("active")->default(1);
			$table->timestamps();
			$table->foreign('member_group_id')->references('id')->on('member_groups');
		});

		Schema::create('tasks', function(Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->integer("member_id")->unsigned()->nullable();
			$table->integer("task_type_id")->unsigned();
			$table->date("date");
			$table->time("start");
			$table->time("stop");
			$table->integer("repeat_in")->nullable->unsigned();
			$table->string("comment",128)->nullable();
			$table->tinyInteger("status")->default(0)->unsigned();
			$table->foreign('member_id')->references('id')->on('members');
			$table->foreign('task_type_id')->references('id')->on('task_types');
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
		Schema::dropIfExists('tasks');
		Schema::dropIfExists('task_types');
	}

}
