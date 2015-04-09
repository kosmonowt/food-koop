@extends('emails.layouts.mail')
@include("emails.layouts.usermail")

@section("heading")
@if (!$positive)
Dein Gruppenkonto wurde Belastet
@else
Wir haben Deine Zahlung über {{$balance}}€ erhalten
@endif
@stop

@section("content")
<p>Kontobewegung in Deinem Bestellgruppenkonto.</p>
<p>Datum: {{$date}}<br>
Betreff: {{$vwz}}<br>
Betrag:&nbsp;
@if (!$positive)
<span class="deductedBalance">{{$balance}} €</span>
@else
<span class="addedBalance">{{$balance}} €</span>
@endif
<br>

@if (!$positive)
<p>Bitte zahle den überfälligen Betrag auf umgehend auf dem Konto der Biokiste ein.</p>
@endif

@if ( (strpos("Mitgliedsbeitrag",$vwz) === 0) && (!$positive) )
<p>Wenn du einen Dauerauftrag geschaltet hast sollte alles seinen Gang gehen.</p>
@endif

@stop