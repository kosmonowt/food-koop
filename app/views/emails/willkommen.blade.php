@extends('emails.layouts.mail')
@include("emails.layouts.usermail")

@section("heading")
	Willkommen in der Biokiste,  {{$user['fistname']}}
@stop

@section("content")
	<p>Lieber {{$user['firstname']}},<br>
	Du bekommst diese E-Mail weil Du ab sofort als Mitglied in einer Bestellgruppe der Biokiste geführt bist.</p>
	<p><strong>Jedes Mitglied einer Bestellgruppe einen eigenen Benuterzugang mit speziellen Rechten.</strong> Falls Deine Mitbewohner / Partner oder andere Gemeinschaftsgefährten keine E-Mail bekommen haben müsstest Du sie noch registrieren.</p>
	<p>HIER KOMMT NOCH DIE MITGLIEDERLISTE REIN</p>
	<p>LOGGE DICH HIER EIN:</p>
	<p>Wir mussten Dein Passwort (wahrscheinlich) ändern. Es lautet nun: {{$password}}</p>
	<p>Der Login ist {{$user['email']}}.</p>
	<p>Liebe Grüße und viel Freude!</p>
	<p>Deine Biokiste</p>
@stop