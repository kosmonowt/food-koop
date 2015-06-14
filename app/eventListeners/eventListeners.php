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
 * This catches when a member is created and 
 * - adds the first ledger entry ("Starteinlage")
 **/
Member::created(function($member){
	$ML = new MemberLedger();
	$ML->date = $member->date_of_entry;
	$ML->member_id = $member->id;
	$ML->balance = Input::get("initialLedger");
	$ML->transaction_type_id = TransactionType::where("shortname","LIKE","D")->pluck("id");
	$ML->vwz = "Starteinlage";
	$ML->save();
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

	$template = Config::get("emails_willkommen_template","emails.willkommen");

	Mail::queue($template, array("user"=>$user,"password"=>$password,"userName"=>$user->username), function($message) use ($firstname,$lastname,$email) {
    	$message->to($email, $firstname." ".$lastname)->subject('BIOKISTE: Dein neuer Zugang, '.$firstname);
	});

	return $user;

});
