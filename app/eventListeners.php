<?php
/**
 * This file is used to register Event Listeners
 * 
 * @since Version 0.2
 * @author Kosmo
 **/



Order::creating(function($order) {
	$product_id = $order->product_id;
	if (!Order::where("product_id","=",$product_id)->count()) {
		$order->comment = (strlen($order->comment)) ? $order->comment . " Erstbestellung." : "Erstbestellung.";
	}
	return $order;
});

Event::listen('orders.setState',function($orders){
	foreach ($orders as $order) {
		// Bestellgruppe Informieren
		// User Informieren
	}
});

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