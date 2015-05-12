<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrateFromVersion2 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		if (!Schema::hasTable('member_groups'))
		Schema::create('member_groups', function($table){
			$table->integer('id')->unsigned();
			$table->string('name',24);
			$table->string('description',255);
			$table->boolean('scheduled_service');
			$table->primary('id');
		});

		if (!Schema::hasTable('member_status'))
		Schema::create('member_status', function($table){
			$table->increments('id')->unsigned();
			$table->string('name',24);
			$table->string('description',255);
			$table->boolean('no_fees')->default(0);
		});

		if (!Schema::hasTable('members'))
		Schema::create('members', function($table) {
			$table->increments("id")->unsigned();
			$table->string('name', 255);
			$table->string('email',255)->nullable();
			$table->string('street',255);
			$table->string('telephone',24)->nullable();
			$table->string('plz',5);
			$table->string('ort',255);
			$table->integer('member_group_id')->unsigned();
			$table->date('date_of_entry');
			$table->integer('member_status_id')->unsigned()->default(1);
			$table->timestamps();
			$table->softDeletes();
			$table->unique('email');
			$table->foreign('member_group_id')->references('id')->on('member_groups');
			$table->foreign('member_status_id')->references('id')->on('member_status');
		});

		if (!Schema::hasTable("member_ledger"))
		Schema::create('member_ledger', function($table) {
			$table->increments('id')->unsigned();
			$table->date('date');
			$table->integer('member_id')->unsigned();
			$table->float('balance');
			$table->string('vwz',255);
			$table->softDeletes();
			$table->timestamps();
		});
		
		if (!Schema::hasTable('user_groups'))
		Schema::create('user_groups',function($table){
			$table->integer('id')->unsigned();
			$table->string('name',64);
			$table->primary("id");
		});

		if (!Schema::hasTable('users'))
		Schema::create('users', function($table) {
			$table->increments("id")->unsigned();
			$table->integer('member_id')->unsigned();
			$table->integer('user_group_id')->unsigned();
			$table->string('username',64);
			$table->string('firstname', 64);
			$table->string('lastname',64);
			$table->string('password',192);
			$table->string('email',255)->nullable();
			$table->string('telephone',24)->nullable();
			$table->rememberToken();
			$table->timestamps();
			$table->datetime('last_login')->nullable();
//    		$table->unique('email');
    		$table->foreign('member_id')->references('id')->on('members');
    		$table->foreign('user_group_id')->references('id')->on('user_groups');
		});


		if (!Schema::hasTable('merchants'))
		Schema::create('merchants', function($table) {
			$table->increments('id')->unsigned();
			$table->string('name',128);
			$table->string('description',512);
			$table->boolean('active')->default(true);
			$table->timestamps();
			$table->softDeletes();
		});

		if (!Schema::hasTable('product_states'))
		Schema::create('product_states', function($table) {
			$table->smallInteger('id')->unsigned();
			$table->string('name',64);
			$table->primary('id');
		});

		if (!Schema::hasTable('product_types'))
		Schema::create("product_types", function($table) {
			$table->increments('id')->unsigned();
			$table->string('shortname',3);
			$table->string('name',64);
			$table->smallInteger('tax')->unsigned();
			$table->unique('shortname');
		});

		if (!Schema::hasTable('products'))
		Schema::create('products', function($table) {
			$table->increments('id')->unsigned();
			$table->integer('merchant_id')->unsigned();
			$table->integer('product_type_id')->unsigned()->default(1);
			$table->smallInteger('product_state_id')->unsigned()->default(1);
			$table->string('sku',128);
			$table->string('name',255);
			$table->string('comment',512)->nullable();
			$table->decimal('price',5,2)->nullable();
			$table->smallInteger('units')->unsigned()->nullable();
			$table->float('weight_per_unit')->unsigned()->nullable();
			$table->enum('unit_unit',array("g","kg","ml","l","Stk"))->default("Stk");
			$table->enum('tare_unit',array("g","kg","ml","l","Stk"))->default("g");
			$table->timestamps();
			$table->softDeletes();
			$table->unique(array('merchant_id','sku'));
			$table->index('name');
			$table->foreign('merchant_id')->references('id')->on('merchants');
			$table->foreign('product_type_id')->references('id')->on('product_types');
			$table->foreign('product_state_id')->references('id')->on('product_states');
		});

		if (!Schema::hasTable('order_states'))
		Schema::create('order_states', function($table) {
			$table->smallInteger('id')->unsigned();
			$table->string('name',64);
			$table->primary("id");
		});

		if (!Schema::hasTable('orders'))
		Schema::create('orders', function($table) {
    		$table->increments('id')->unsigned();
    		$table->integer("member_id")->unsigned();
    		$table->integer("user_id")->unsigned();
    		$table->integer("product_id")->unsigned();
    		$table->integer("merchant_id")->unsigned();
    		$table->smallInteger("order_state_id")->unsigned()->default(0);
    		$table->smallInteger("amount")->unsigned();
    		$table->string('comment', 255)->nullable();
    		$table->timestamps();
    		$table->softDeletes();
			$table->foreign('member_id')->references('id')->on('members');
			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('product_id')->references('id')->on('products');
			$table->foreign('merchant_id')->references('id')->on('merchants');
			$table->foreign('order_state_id')->references('id')->on('order_states');
		});



		//This is only when migrating from other versions
		if (Schema::hasTable('bestellungen')) {			
			Schema::table('bestellungen', function($table) { 
				
				if (Schema::hasColumn('bestellungen',"datetime")) 	
					$table->renameColumn('datetime', 'created');
			 	
			 	if (Schema::hasColumn('bestellungen',"mitglied")) 	
			 		$table->renameColumn('mitglied', 'mitglied_id');
			 	
			 	if (Schema::hasColumn('bestellungen',"date")) 		
			 		$table->dropColumn('date');
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

		Schema::dropIfExists('member_ledger');
		Schema::dropIfExists('orders');
		Schema::dropIfExists('order_states');
		Schema::dropIfExists('products');
		Schema::dropIfExists('product_states');
		Schema::dropIfExists('product_types');
		Schema::dropIfExists('merchants');
		Schema::dropIfExists('users');
		Schema::dropIfExists('members');
		Schema::dropIfExists('user_groups');
		Schema::dropIfExists('member_groups');

	}

}
