@extends('emails.layouts.mail')
@include("emails.layouts.usermail")
@include("emails.includes.ordersList")

@section("heading")
Deine Bestellung bei {{$merchantName}} ist eingetroffen!
@stop

@section("content")
	<p>Deine
	@if (count($orders) === 1)
	Bestellung
	@else
	Bestellungen
	@endif
	bei
	{{$merchantName}}</p>
	@yield("ordersList")
	<p>ist soeben eingetroffen.</p>
	<p>Bitte hole diese bei nächster Gelegenheit in der Biokiste ab.</p>
@stop