@extends('layouts.biokiste')
@include("includes.header")

@section("content")
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<strong>Willkommen</strong>
		</div>
		 <div class="panel-body">
			<p>Willkommen auf den neuen Seiten der Biokiste.</p>
			<p>Außen hat sich viel verändert, innen jedoch noch viel mehr!</p>
			<p>
		</div>		
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<strong>Neuigkeiten</strong>
		</div>
		<div class="panel-body">
			@foreach ($news as $content)
			<article class="article" id="article_@{{id}}">
				<h4>{{$content->name}}</h4>
				<p><small>Geschrieben am {{$content->created_at}} von {{$content->author}}</small></p>
					{!!$content->parsedContent!!}
			</article>
			@endforeach
		</div>
	</div>	
</div>
@stop