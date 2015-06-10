<?php
/**
 * In this files all Event listeners are registered that trigger sending emails
 **/

/**
 * This catches a changed state of an order
 * - inform the member (on change to "ordered")
 * - inform the user (on change to "ordered")
 * - don't inform the user (when he fired the event)
 **/
Event::listen('orders.setState',function($orders,$new_order_state){
	if ($new_order_state >= 5 || $new_order_state == 3 || $new_order_state == 100) {
		
		if ($new_order_state == 5) {
			// Message => ORDERED
			$mailTemplateName = "event_order_ordered";
			$mailSubject = "BIOKISTE: Deine Bestellung wurde aufgegeben.";
		} elseif ($new_order_state == 3) {
			$mailTemplateName = "event_order_setback";
			$mailSubject = "BIOKISTE: Deine Bestellung wurde zurückgestellt.";
		} elseif ($new_order_state == 100) {
			// Message => ARRIVED
			$mailTemplateName = "event_order_arrived";
			$mailSubject = "BIOKISTE: Deine Bestellung ist angekommen.";
		}

		// Sort Order By Member
		$ordersByMember = array();
		foreach ($orders as $order) {
			$order['product'] = Product::find($order['id']);
			$ordersByMember[$order->member_id][] = $order;
		}
		// For each member send only one mail
		// The user that triggers this event is not informed
		foreach ($ordersByMember as $memberOrders) {
			$order = current($memberOrders);
			$member = Member::where("id","=",$order->member_id)->first();
			$user = User::where("id","=",$order->user_id)->first();

			$recipients = array();
			if (Auth::user()->email != $user->email && !is_null($user->email)) $recipients[] = trim($user->email);
			if ( (Auth::user()->email != $member->email) && ($user->email != $member->email) ) $recipients[] = trim($member->email);

			if (count($recipients))
				Mail::queue(
					"emails.$mailTemplateName",
					array("merchantName" => $order->merchant->name,"orders" => $memberOrders,"userName" => $user->name), 
					function($message) use ($recipients,$mailSubject) { $message->to($recipients)->subject($mailSubject);}
				);
		}
	}
});

Event::listen("orders.massDeleted",function($orders) {

	foreach ($orders as $order) {
		$user = User::find($order->user_id);
		$name = $user->name;
		$email = $user->email;

		Mail::queue(
			"emails.event_order_deleted",
			array("merchantName" => $order->merchant->name,"order" => $order,"userName" => $user->name),
			function($message) use ($name,$email) {
				$message->from(Config::get("order_admin_email"))->to($email)->subject("BIOKISTE: Deine Bestellung wurde gelöscht, ".$name);
			}
		);

	}
});

/**
 * This event is triggered when someone removes himself from a task
 * 
 **/
Event::listen('tasks.unassign',function($task) {
	$daysToInform = Setting::where("key","like","task_unassign_information_mail")->pluck("value");
	$taskDate = new DateTime($task->date);
	$dueDate = new DateTime ("NOW + $daysToInform DAYS"); // less then 14 days before the task we will send an email if task is without member now
	if ($dueDate >= $taskDate) {
		
	}
});

/**
 * This event is always fired when a transaction is made on a member ledger
 **/
MemberLedger::saving(function($memberLedger) {

	$positive = ($memberLedger->balance > 0);
	$member = Member::find($memberLedger->member_id);

	if (is_object($member)) {
	
		$email = $member->email;
		$userName = $member->name;

		Mail::queue(
			"emails.event_memberLedger_deduction",
			array("vwz" => $memberLedger->vwz,"balance"=>$memberLedger->balance,"date"=>$memberLedger->date,"userName"=>$userName,"positive"=>$positive),
			function($message) use ($email) { 
				$message->to($email)->subject("BIOKISTE: Neue Kontobewegung in Deinem Mitgliedskonto"); 
			}
		);

		Log::info("ledger action mail sent: ".$member->email);

	}
});

/**
 * This catches when user WAS created.
 * Sends Mail out to admin
 **/
User::created(function($user){

	$member = Member::where("id",$user->member_id)->with("user")->first();
	$name = $member->name;
	$email = Config::get("member_admin_email");

	Mail::queue(
		"emails.event_member_new_member",
		array("member"=>$member,"newMember"=>$user),
		function($message) use ($name,$email) {
			$message->to($email)->subject("BIOKISTE: Neues Mitglied für Gruppe ".$name);
		}
	);

	return $user;

});