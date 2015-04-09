@extends('emails.layouts.mail')
@include("emails.layouts.usermail")

@section("heading")
Neues Mitglied in Dienstgruppe {{$member->name}}
@stop

@section("content")
<p>Soeben wurde ein neues Mitglied in der Gruppe {{$member->name}} registriert.</p>

<p>{{$newMember->name}}, {{$newMember->email}}</p>

<p>Die Dienstgruppe besteht nun aus {{count($member->user)}} Mitgliedern.</p>

<ul>
@foreach($member->user as $user)
	<li>{{$user->name}}, {{$user->email}}</li>
@endforeach
</ul>

@stop