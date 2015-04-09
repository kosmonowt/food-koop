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
	if ($new_order_state >= 4 || $new_order_state == 2) {
		if ($new_order_state == 4) {
			// Message => ORDERED
			$mailTemplateName = "event_order_ordered";
			$mailSubject = "BIOKISTE: Deine Bestellung wurde aufgegeben.";
		} elseif ($new_order_state == 2) {
			$mailTemplateName = "event_order_setback";
			$mailSubject = "BIOKISTE: Deine Bestellung wurde zurückgestellt.";
		} elseif ($new_order_state == 100) {
			// Message => ARRIVED
			$mailTemplateName = "event_order_arrived";
			$mailSubject = "BIOKISTE: Deine Bestellung ist angekommen.";
		}

		// Sort Order By Member
		$ordersByMember = array();
		foreach ($orders as $order) $ordersByMember[$order->member_id][] = $order;
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
});