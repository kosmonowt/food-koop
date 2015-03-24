<?php
/**
 * This file is used to register Event Listeners
 * 
 * @since Version 0.2
 * @author Kosmo
 **/


/**
 * This catches created order event
 * - adds a Tag on the order if the product is ordered first time
 **/
Order::creating(function($order) {
	$product_id = $order->product_id;
	if (!Order::where("product_id","=",$product_id)->count()) {
		$order->comment = (strlen($order->comment)) ? $order->comment . " Erstbestellung." : "Erstbestellung.";
	}
	return $order;
});

/**
 * This catches a changed state of an order
 * - inform the member (on change to "ordered")
 * - inform the user (on change to "ordered")
 * - don't inform the user (when he fired the event)
 **/
Event::listen('orders.setState',function($orders){
	foreach ($orders as $order) {
		// Bestellgruppe Informieren
		// User Informieren
		// User nicht informieren, wenn er das ausgelöst hat
	}
});

Event::listen('tasks.unassign',function($task){

	$taskDate = new DateTime($task->date);
	$dueDate = new DateTime ("NOW + 14 DAYS"); // less then 14 days before the task we will send an email if task is without member now
	if ($dueDate >= $taskDate) {
		// Create E-Mail 
	}

});

/**
 * This catches created User event
 * - creates password hash
 * - creates email to member (on migration)
 **/
User::creating(function($user){
	$password = $user->password;
	$user->password = Hash::make($password);

	$firstname = $user->firstname;
	$lastname = $user->lastname;
	$email = $user->email;

	Mail::queue('emails.willkommen_migrated', array("user"=>$user,"password"=>$password), function($message) use ($firstname,$lastname,$email) {
    	$message->to($email, $firstname." ".$lastname)->subject('Dein Zugang zur Biokiste, '.$firstname);
	});

	return $user;

});

/** 
 * This catches when a member is created and 
 * - adds the first ledger entry ("Starteinlage")
 **/
Member::created(function($member){
	$ML = new MemberLedger();
	$ML->date = $member->date_of_entry;
	$ML->member_id = $member->id;
	$ML->balance = Input::get("initialLedger");
	$ML->vwz = "Starteinlage";
	$ML->save();
});
