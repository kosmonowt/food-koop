<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLedgerVWZStandards extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('ledgerVwzs')) {

			Schema::create('ledgerVwzs', function($table){
				$table->integer('id')->unsigned();
				$table->string('name',64);
				$table->unique('name');
				$table->primary('id');
			});

		}

	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('ledgerVwzs');
	}

}
