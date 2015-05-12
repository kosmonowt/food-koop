<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('settings');

		Schema::create('settings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string("key")->unique();
			$table->string("value",512);
			$table->string("description")->nullable();
			$table->enum('type', array('int', 'float', 'array', 'string'));
			$table->timestamps();

		});

		$s = new Setting();
		$s->key = "general_sitename";
		$s->description = "Name der FoodKoop";
		$s->value = "Biokiste";
		$s->type = "string";
		$s->save();

		$s = new Setting();
		$s->key = "general_url";
		$s->description = "URL der FoodKoop";
		$s->value = "biokiste.localhost";
		$s->type = "string";
		$s->save();		

		$s = new Setting();
		$s->key = "memberLedger_fee";
		$s->description = "Mitgliedsbeitrag in €";
		$s->value = 21;
		$s->type = "float";
		$s->save();

		$s = new Setting();
		$s->key = "memberLedger_months";
		$s->description = "Abrechnung des Mitgliedsbeitrag all n Monate";
		$s->value = 3;
		$s->type = "int";
		$s->save();	

		$s = new Setting();
		$s->key = "memberLedger_vwz_default_positive";
		$s->description = "Verwendungszweck bei Einzahlung in Mitgliedskonto";
		$s->value = "Einzahlung";
		$s->type = "string";
		$s->save();

		$s = new Setting();
		$s->key = "memberLedger_vwz_default_negative";
		$s->description = "Verwendungszweck bei Abbuchung von Mitgliedskonto";
		$s->value = "Einzahlung";
		$s->type = "string";
		$s->save();

		$s = new Setting();
		$s->key = "memberLedger_vwz_member_fee";
		$s->description = "Verwendungszweck Für Mitgliedsbeitrag";
		$s->value = "Mitgliedsbeitrag";
		$s->type = "string";
		$s->save();

		$s = new Setting();
		$s->key = "memberLedger_balance_start";
		$s->description = "Starteinlage für Mitgliedskonto";
		$s->value = "50";
		$s->type = "float";
		$s->save();

		$s = new Setting();
		$s->key = "memberLedger_vwz_start";
		$s->description = "Verwendungszweck für Starteinlage";
		$s->value = "Starteinlage";
		$s->type = "string";
		$s->save();

		$s = new Setting();
		$s->key = "email_member_admin";
		$s->description = "Email Adresse der Mitgliederverwaltung";
		$s->value = "mitglieder@biokiste.org";
		$s->type = "string";
		$s->save();

		$s = new Setting();
		$s->key = "email_task_admin";
		$s->description = "Email Adresse der Dienstplanverwaltung";
		$s->value = "dienstplan@biokiste.org";
		$s->type = "string";
		$s->save();

		$s = new Setting();
		$s->key = "email_order_admin";
		$s->description = "Email Adresse der Bestellungsverwaltung";
		$s->value = "bestellung@biokiste.org";
		$s->type = "string";
		$s->save();

		$s = new Setting();
		$s->key = "task_unassign_information_mail";
		$s->description = "Ab wievielen Tagen im Voraus soll die Dienstplanverwaltung bei Austragen aus einem Dienst informiert werden";
		$s->value = "14";
		$s->type = "int";
		$s->save();

	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('settings');
	}

}
