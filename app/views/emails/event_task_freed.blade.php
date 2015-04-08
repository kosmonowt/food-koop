@extends('layouts.mail')
@include("layouts.adminmail")

@section("heading")
Dienst am {{$task->date}} wieder freigegeben
@stop

@section("content")
<p>Der bereits von <em>{{$task->member->name}}</em> belegte Dienst <strong>{{$task->taskType->short_description}} am {{$task->date}}</strong><br>
wurde soeben vom Gruppenmitglied <em>{{$triggeringUser->name}}</em> wieder freigegeben.</p>
<p>Bitte schalte wenn nötig eine Nachricht in der Email Gruppe.</p>
@stop