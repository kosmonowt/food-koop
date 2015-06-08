@extends('emails.layouts.mail')
@include("emails.layouts.usermail")
@include("emails.includes.ordersList")

@section("heading")
Deine Bestellung bei {{$merchantName}} ist zurückgestellt worden.
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
	<p>wurde zurückgestellt.</p>
	<p>Bei Fragen dazu stelle diese bitte an bestellgruppe@biokiste.org.</p>
@stop