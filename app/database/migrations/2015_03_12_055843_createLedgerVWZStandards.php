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
				$table->increments('id')->unsigned();
				$table->string('name',64);
				$table->unique('name');
			});

			$lvwz = new LedgerVwz();
			$lvwz->name = "Starteinlage";
			$lvwz->save();

			$lvwz = new UserGroup();
			$lvwz->name = "Einzahlung";
			$lvwz->save();		

			$lvwz = new UserGroup();
			$lvwz->name = "Belastung";
			$lvwz->save();

			$lvwz = new LedgerVwz();
			$lvwz->name = "Quartalsbeitrag";
			$lvwz->save();

			$lvwz = new LedgerVwz();
			$lvwz->name = "Auszahlung Starteinlage";
			$lvwz->save();

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
