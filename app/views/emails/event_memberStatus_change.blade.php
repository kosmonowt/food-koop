@extends('emails.layouts.mail')
@include("emails.layouts.membermail")

@section("heading")
Änderung Deines Mitgliedsstatus
@stop

@section("content")
<p>Dein Mitgliedsstatus wurde soeben von {{$statusNameBefore}} auf {{$statusNameCurrent}} geändert.</p>
<p>Bei Fragen dazu wende dich bitte an die MitgliederVerwaltung {{$emailMemberManagement}}.</p>
@stop