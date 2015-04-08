@extends('layouts.mail')
@include("layouts.usermail")

@section("heading")
@if ($memberLedger->balance < 0)
Dein Gruppenkonto wurde Belastet
@else
Wir haben Deine Zahlung über {{$memberLedger->balance}}€ erhalten
@endif
@stop

@section("content")
<p>Kontobewegung in Deinem Bestellgruppenkonto.</p>
<p>Datum: {{$memberLedger->date}}<br>
Betreff: {{$memberLedger->vwz}}<br>
Betrag:&nbsp;
@if ($memberLedger->balance < 0)
<span class="deductedBalance">{{$memberLedger->balance}} €</span>
@else
<span class="addedBalance">{{$memberLedger->balance}} €</span>
@endif
<br>

@if ($memberLedger->balance < 0)
<p>Bitte zahle den überfälligen Betrag auf umgehend auf dem Konto der Biokiste ein.</p>
@endif

@if ( (strpos("Mitgliedsbeitrag",$memberLedger->vwz) === 0) && ($memberLedger->balance < 0) )
<p>Wenn du einen Dauerauftrag geschaltet hast sollte alles seinen Gang gehen.</p>
@endif

@stop