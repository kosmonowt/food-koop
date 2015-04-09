<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContentModel extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create('content_types', function(Blueprint $table) {
			$table->increments("id");
			$table->string("name");
			$table->string("description")->nullable();
		});

		$c = new ContentType();
		$c->name = "Öffentlcher Post";
		$c->description = "Dieser Inhaltstyp ist für Inhalte, die auf der öffentlichen Startseite erscheinen.";
		$c->save();

		$c = new ContentType();
		$c->name = "Dashboard Post";
		$c->description = "Dieser Inhaltstyp ist für Inhalte, bei den Mitgliedern im Dashboard erscheinen.";
		$c->save();

		$c = new ContentType();
		$c->name = "Inhaltsseite";
		$c->description = "Dieser Inhaltstyp stellt Inhalte dar, die eine eigene Unterseite darstellen.";
		$c->save();

		Schema::create('content', function(Blueprint $table) {
			$table->increments('id');
			$table->string("name");
			$table->text("content");
			$table->string("permalink")->nullable();
			$table->integer("type_id")->unsigned();
			$table->integer("created_by")->unsigned();
			$table->boolean("is_published")->default(true);
			$table->datetime("published_at")->nullable();
			$table->datetime("unpublished_at")->nullable();
			$table->timestamps();
			$table->foreign('created_by')->references('id')->on('users');
			$table->foreign('type_id')->references('id')->on('content_types');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('content');
		Schema::dropIfExists('content_types');
	}

}
