@extends('emails.layouts.mail')
@include("emails.layouts.usermail")

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
	@yield("includes.ordersList")
	<p>wurde zurückgestellt.</p>
	<p>Bei Fragen dazu stelle diese bitte an bestellgruppe@biokiste.org.</p>
@stop