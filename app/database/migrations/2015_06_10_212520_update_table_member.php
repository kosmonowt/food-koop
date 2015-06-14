<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateTableMember extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasColumn("members","groupUID")) {

			Schema::table('members', function(Blueprint $table) {
				$table->string('groupUID', 6)->after("id");
			});

		}

		Member::all()->each(function ($m) {
			$m->groupUID = strtoupper(substr($m->name, 0,2)).$m->id;
			$m->save();
		});

		Schema::table("members", function(Blueprint $table) {
			$table->unique('groupUID');
		});

	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('members', function(Blueprint $table)
		{
			$table->dropColumn('groupUID');
		});
	}

}
