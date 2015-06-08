@extends('emails.layouts.mail')
@include("emails.layouts.usermail")
@include("emails.includes.ordersList")

@section("heading")
Deine Bestellung bei {{$merchantName}} wurde aufgegeben.
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
	<p>wurde soeben bestellt.</p>
	<p>Wenn alles gut geht sollte die Ware bei der nächsten Lieferung mit ankommen.</p>
@stop