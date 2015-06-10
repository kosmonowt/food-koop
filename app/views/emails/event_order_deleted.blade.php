@extends('emails.layouts.mail')
@include("emails.layouts.usermail")

@section("heading")
Deine Bestellung bei {{$merchantName}} wurde gelöscht.
@stop

@section("content")
	<p>Deine Bestellung	bei {{$merchantName}}</p>
	<ul>
		@include('emails.includes.order', array('order'=>$order))
	</ul>
	<p>wurde soeben von uns gelöscht.</p>
	<p>Bitte kontaktiere bestellung@biokiste.org um zu erfahren warum.</p>
@stop