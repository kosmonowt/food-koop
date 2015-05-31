<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContentAndTaskSeeds extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$c = new Content();
		$c->name = "Willkommen in Biokiste Version 3";
		$c->content = 
"Herzlich Willkommen in der rohen Version der Biokistensoftware.

Diese soll Dich unterstützen

* Bestellungen besser tätigen und verwalten zu können
* Mitglieder besser verwalten zu können
* Den Kontostand der Mitglieder besser verwalten zu können
* Den Dienstplan besser verwalten zu können.

Die Software ist noch lange nicht fertig und basiert auf hunderten Einsatzstunden seines Programmieres.

#Ich brauche Feedback#

*schreibt eine mail an web@biokiste.org* wenn Ihr auf Probleme stoßt!

Bis dahin, viel Spaß mit diesem Programm!";
		$c->type_id = 2;
		$c->created_by = 1;
		$c->is_published = 1;
		$c->save();

		$t = new TaskType();
		$t->name = "Auspackdienst";
		$t->short_description = "Der Auspackdienst. Packen und anpacken!";
		$t->long_description = "Hier kann ein langer langer langer Text stehen. Mitunter eine ganze Anleitung!";
		$t->time_start = "13:00:00";
		$t->time_stop = "15:00:00";
		$t->day_of_week = 4;
		$t->member_group_id = 2;
		$t->active = 1;
		$t->save();

	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	}

}
