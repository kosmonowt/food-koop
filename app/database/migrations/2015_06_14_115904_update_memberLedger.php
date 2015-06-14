<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateMemberLedger extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		if (!Schema::hasTable("transaction_types")) {

			Schema::create('transaction_types', function(Blueprint $table) {
				$table->increments('id')->unsigned();
				$table->string("name",64);
				$table->string("shortname",1);
			});

			$t = new TransactionType();
			$t->name = "Starteinlage / Darlehen";
			$t->shortname = "D";
			$t->save();

			$t = new TransactionType();
			$t->name = "Mitgliedbeitrag";
			$t->shortname = "B";
			$t->save();

			$t = new TransactionType();
			$t->name = "Einkauf";
			$t->shortname = "E";
			$t->save();

			$t = new TransactionType();
			$t->name = "Guthaben";
			$t->shortname = "G";
			$t->save();

			$t = new TransactionType();
			$t->name = "Unbekannt";
			$t->shortname = "X";
			$t->save();

		}

		if (!Schema::hasColumn("member_ledger","transaction_type_id")) {

			Schema::table('member_ledger', function(Blueprint $table) {
				$table->integer('transaction_type_id')->unsigned()->default(TransactionType::where('shortname', 'X')->pluck('id'));
				$table->index('transaction_type_id');
				$table->foreign('transaction_type_id')->references('id')->on('transaction_types');
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
		if (Schema::hasColumn('member_ledger', 'transaction_type_id')) {

			Schema::table('member_ledger', function(Blueprint $table) {
				$table->dropColumn("transaction_type_id");
			});

		}

		Schema::dropIfExists('transaction_types');
	}

}
