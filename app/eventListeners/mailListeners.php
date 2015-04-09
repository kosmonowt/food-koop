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

	if ($new_order_state == 4) {
		// Message => ORDERED
		$mailTemplateName = "event_order_ordered";
		$mailSubject = "BIOKISTE: Deine Bestellung wurde aufgegeben.";
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
		if (Auth::user()->email != $user->email) $recipients[] = $user->email;
		if ( (Auth::user()->email != $member->email) && ($user->email != $member->email) ) $recipients[] = $member->email;

		if (count($recipients))
			Mail::queue(
				"emails.$mailTemplateName",
				array("merchantName" => $order->merchant->name,"orders" => $memberOrders,"userName" => $user->name), 
				function($message) use ($recipients,$mailSubject) { $message->to($recipients)->subject($mailSubject);}
			);

	}

});

/**
 * This event is triggered when someone removes himself from a task
 * 
 **/
Event::listen('tasks.unassign',function($task){
	$daysToInform = Setting::where("key","like","task_unassign_information_mail")->pluck("value");
	$taskDate = new DateTime($task->date);
	$dueDate = new DateTime ("NOW + $daysToInform DAYS"); // less then 14 days before the task we will send an email if task is without member now
	if ($dueDate >= $taskDate) {
		
	}

});