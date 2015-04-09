@extends('layouts.mail')
@include("layouts.usermail")

@section("heading")
Deine Bestellung bei {{merchantName}} wurde aufgegeben.
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
	<p>wurde soeben bestellt.</p>
	<p>Wenn alles gut geht sollte die Ware bei der nächsten Lieferung mit ankommen.</p>
@stop