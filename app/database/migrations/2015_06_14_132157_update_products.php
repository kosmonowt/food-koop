<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateProducts extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('products', function(Blueprint $table)
		{
			$table->string("update_code",1)->nullable()->after("tare_unit");
			$table->datetime("updated_at_source")->nullable()->after("update_code");
			$table->string("ean",32)->nullable()->after("update_code");
			$table->smallInteger("hkl")->nullable()->unsigned()->after("update_code");
			$table->string("manufacturer_name",4)->nullable()->after("update_code");
			$table->string("origin",3)->nullable()->after("update_code");
			$table->string("quality",4)->nullable()->after("update_code");
			$table->float("min_order_quantity")->nullable()->unsigned()->after("update_code");
			$table->date("order_from")->nullable()->after("update_code");
			$table->date("order_to")->nullable()->after("update_code");
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('products', function(Blueprint $table)
		{
			$table->dropColumn("update_code");
			$table->dropColumn("updated_at_source");
			$table->dropColumn("ean");
			$table->dropColumn("hkl");
			$table->dropColumn("manufacturer_name");
			$table->dropColumn("origin");
			$table->dropColumn("quality");
			$table->dropColumn("min_order_quantity");
			$table->dropColumn("order_from");
			$table->dropColumn("order_to");
		});
	}

}
