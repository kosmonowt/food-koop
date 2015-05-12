<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveDataAndClearOldTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		// Copy Member Workgroups
		if (Schema::hasTable('dienstgruppen')) {
			$dienstgruppen = DB::table("dienstgruppen")->get();
			foreach($dienstgruppen as $dg) {
				$g1 = new MemberGroup();
				$g1->id = $dg->id;
				$g1->name = $dg->name;
				$g1->description = $dg->beschreibung;
				$g1->scheduled_service = $dg->orga;
				$g1->save();
			}

		}

		// User Groups (initial)
		$ug = new UserGroup();
		$ug->id = 1;
		$ug->name = "Mitglied";
		$ug->save();

		$ug = new UserGroup();
		$ug->id = 2;
		$ug->name = "Backend Level 1";
		$ug->save();		

		$ug = new UserGroup();
		$ug->id = 3;
		$ug->name = "Administrator";
		$ug->save();		

		// Member Status (initial)
		$ug = new MemberStatus();
		$ug->name = "Reguläres Mitglied";
		$ug->save();

		$ug = new MemberStatus();
		$ug->name = "Ehrenmitglied";
		$ug->description = "Zahlt keinen Beitrag";
		$ug->no_fees = true;
		$ug->save();

		$ug = new MemberStatus();
		$ug->name = "Mitgliedschaft ruhend";
		$ug->description = "Mitglied, welches noch Mitglied ist, aber derzeit nicht zahlt, da länger auf Reisen o.Ä. Zahlt keinen Beitrag.";		
		$ug->no_fees = true;
		$ug->save();

		$ug = new MemberStatus();
		$ug->name = "Gesperrtes Mitglied";
		$ug->description = "Mitglied, welches noch Mitglied ist, aber derzeit von Aktionen gesperrt ist.";
		$ug->save();		

		if (Schema::hasTable('mitglieder')) {
			// Datenübernahme

			$mitglieder = DB::table('mitglieder')->get();

			$memberMap = array();

			 foreach($mitglieder as $mitglied) {

			 	$member = new Member();
			 	$member->name = ($mitglied->gruppenname != "") ? $mitglied->gruppenname : $mitglied->vorname." ".$mitglied->nachname;
			 	$member->email = $mitglied->email;
				$member->telephone = $mitglied->telefon;
				$member->street = $mitglied->strasse;
				$member->plz = $mitglied->plz;
				$member->ort = $mitglied->ort;
				$member->date_of_entry = $mitglied->beitrittsdatum;
				$member->member_group_id = $mitglied->gruppe;
				$member->save();

				$memberMap[$mitglied->id]['member_id'] = $member->id;
				
				// Starteinlage
				$memberLedger = new MemberLedger();
				$memberLedger->member_id = $member->id;
				$memberLedger->balance = floatval($mitglied->beitrag);
				$memberLedger->vwz = "Starteinlage";
				$memberLedger->date = $mitglied->beitrittsdatum;
				$memberLedger->save();

				$gruppenmitglieder = DB::table('gruppenmitglieder')->where('mitgliederID',$mitglied->id)->get();
				foreach($gruppenmitglieder as $key => $gruppenmitglied) {

					// GenericMember
					$user = new User();
					$user->member_id = $member->id;
					$user->username = "gruppe".$member->id."_".$key;
					$user->firstname = $gruppenmitglied->vorname;
					$user->lastname = $gruppenmitglied->nachname;
					$user->telephone = $gruppenmitglied->telefon;
					if ($gruppenmitglied->email !== "") $user->email = $gruppenmitglied->email;
					if (strlen($mitglied->passwort) == 40) {
						$words = ["Apfel","Birne","Kirsche","Banane","Orange","Maulbeere","Brombeere","Yoghurt","Sonnenblume","Hirsch","Pferd","Detektiv","Geranie","Begonie","Kamille","Veilchen","Vergissmeinicht"];
						$number1 = rand(10,99);
						$number2 = rand(10,99);
						$w1 = $words[rand(0,(count($words)-1))];
						$w2 = $words[rand(0,(count($words)-1))];
						$pw = $number1.$w1.$w2.$number2;
					} else {
						$pw = $mitglied->passwort;
					}
					$user->password = $pw;
					$user->user_group_id = 1;
					$user->save();

					if ($key == 0) $memberMap[$mitglied->id]['user_id'] = $user->id;

				}

			 }

		}

		// Seed Order State
		$os = new OrderState();
		$os->id = 0;
		$os->name = "Unbearbeitet";
		$os->save();		
		$os = new OrderState();
		$os->id = 1;
		$os->name = "Wartend";
		$os->save();		
		$os = new OrderState();
		$os->id = 2;
		$os->name = "Zurückgestellt";
		$os->save();		
		$os = new OrderState();
		$os->id = 3;
		$os->name = "Auf Bestelliste";
		$os->save();		
		$os = new OrderState();
		$os->id = 4;
		$os->name = "Bestellt";
		$os->save();
		$os->id = 100;
		$os->name = "Abgeschlossen";
		$os->save();

		// Seed Product State
		$ps = new ProductState();
		$ps->id = 1;
		$ps->name = "Standardprodukt";
		$ps->save();
		$ps = new ProductState();
		$ps->id = 2;
		$ps->name = "Gesperrtes Produkt";
		$ps->save();
		$ps = new ProductState();
		$ps->id = 3;
		$ps->name = "Grundbedarf";
		$ps->save();		

		if (Schema::hasTable('bestellungen')) {

			$warentyp = DB::table("warentyp")->get();

			foreach ($warentyp as $id => $wt) {
				
				$pt = new ProductType();
				$pt->name = $wt->beschreibung;
				$pt->shortname = $wt->kurzbeschreibung;
				$pt->tax = $wt->mwst_satz;
				$pt->save();

				$warentypMap[$wt->id] = $pt->id;

			}

			$anbieter = DB::table("anbieter")->get();

			foreach ($anbieter as $id => $ab) {
				
				$merchant = new Merchant();
				$merchant->name = $ab->name;
				$merchant->description = $ab->text."\n".$ab->wochentag;
				$merchant->active = true;
				$merchant->save();

				$merchantMap[$ab->id] = $merchant->id;

			}

			$bestellungen = DB::table("bestellungen")->get();

			foreach ($bestellungen as $bestellung) {
				// Don't take orders that have an unknown merchant id.
				if (!array_key_exists($bestellung->anbieter, $merchantMap)) continue;

				// Create Product if not Exists
				$product = 
					Product::where("sku",$bestellung->nummer)
					->where('merchant_id',$merchantMap[$bestellung->anbieter])
					->first();

				if (!$product)
					$product = new Product();
					$product->merchant_id = $merchantMap[$bestellung->anbieter];
					$product->product_type_id = $warentypMap[$bestellung->typ];
					$product->sku = $bestellung->nummer;
					$product->name = $bestellung->bezeichnung;
					$product->comment = $bestellung->kommentar;
					$product->price = $bestellung->nettopreis;
					$product->units = $bestellung->gebinde;
					$product->weight_per_unit = $bestellung->gewicht;
					$product->save();

				if ($memberMap[$bestellung->mitglied]['member_id'] > 1) {
					$order = new Order();
					$order->member_id = $memberMap[$bestellung->mitglied]['member_id'];
					$order->user_id = $memberMap[$bestellung->mitglied]['user_id'];
					$order->product_id = $product->id;
					$order->merchant_id = $merchantMap[$bestellung->anbieter];
					$order->order_state_id = $bestellung->status;
					$order->amount = $bestellung->anzahl;
					$order->comment = $bestellung->kommentar;
					$order->created_at = $bestellung->datetime;
					$order->save();

				}

			}

		}
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
