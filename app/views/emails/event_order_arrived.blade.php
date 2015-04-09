@extends('emails.layouts.mail')
@include("emails.layouts.usermail")

@section("heading")
Deine Bestellung bei {{$merchantName}} ist eingetroffen!
@stop

@section("content")
	<p>Deine&nbsp;
	@if (count($orders) === 1)
	Bestellung
	@else
	Bestellungen
	@endif
	&nbsp;bei
	{{$merchantName}}</p>
	@yield("includes.ordersList")
	<p>ist soeben eingetroffen.</p>
	<p>Bitte hole diese bei nächster Gelegenheit in der Biokiste ab.</p>
@stop