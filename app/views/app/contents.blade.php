@extends('layouts.biokiste')
@include('includes.mustache')
@include("includes.bottomJs")
@include("includes.header")

@section("tableHeader")
<tr>
	<th></th>
	<th>Titel</th>
	<th>Datum</th>
	<th>Typ</th>
	<th>Status</th>
	<th>Autor</th>
	<th>Aktion</th>
</tr>
@stop

@section("tableBody")
<tr class="@{{colormark totalAmount}} orderState-@{{order_state_id}}">
	<td onclick="$(this).children('input').click();"><input type="checkbox" value="@{{id}}"></td>
	<td>@{{name}}</td>
	<td>@{{published_at}}</td>
	<td>@{{contentTypeName}}</td>
	<td>@{{status}}</td>
	<td>@{{author}}</td>
	<td>
		<button class="btn btn-success btn-sm" can-click="editContent" ><span class="glyphicon glyphicon-edit" title="Bearbeiten"></span></button>
		<button class="btn btn-warning btn-sm" can-click="delete"><span class="glyphicon glyphicon-remove-sign" title="Löschen"></span></button>
	</td>
</tr>
@stop

@section("index")
<div role="tabpanel" class="tab-pane active" id="public">
	<div class="row">
		<div class="col-xs-12">
			<br>
			<div class="btn-group" role="group" aria-label="Inhaltstyp wählen" id="listContentTypeSelector">
				<button type="button" class="btn btn-default active" can-click="contentTypeSelect" data-val="0" id="btn-all-contents">Alle</button>
				<button type="button" class="btn btn-default" can-click="contentTypeSelect" data-val="1">Öffentlich</button>
				<button type="button" class="btn btn-default" can-click="contentTypeSelect" data-val="2">Für Mitglieder</button>
				<button type="button" class="btn btn-default" can-click="contentTypeSelect" data-val="3">Seiteninhalt</button>
			</div>			
			<br><br>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<table class="table table-striped table-hover" id="byProductTable">
				<tbody>
					@yield("tableHeader")
					@{{#each contents}}
					@yield("tableBody")
				    @{{/each}}
			    </tbody>
			</table>
		</div>
	</div>
</div>
@stop

@section("member")
<div role="tabpanel" class="tab-pane active" id="member">
	<div class="row">
		<div class="col-xs-12">
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<table class="table table-striped table-hover" id="byProductTable">
				<tbody>
					@yield("tableHeader")
					@{{#each memberContents}}
					@yield("tableBody")
				    @{{/each}}
			    </tbody>
			</table>
		</div>
	</div>
</div>
@stop

@section("presentation")
<div role="tabpanel" class="tab-pane active" id="presentation">
	<div class="row">
		<div class="col-xs-12">
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<table class="table table-striped table-hover" id="byProductTable">
				<tbody>
					@yield("tableHeader")
					@{{#each presentationContents}}
					@yield("tableBody")
				    @{{/each}}
			    </tbody>
			</table>
		</div>
	</div>
</div>
@stop

@section("new")
<div role="tabpanel" class="tab-pane" id="new">
	@{{#currentContent}}
	<form class="form-horizontal" action="" can-submit="createContent">
		<div class="row">
			<div class="col-ms-6">
				<h3>Neuer Inhalt</h3>
			</div>
			<div class="col-ms-6">

			</div>							
		</div>
		<div class="form-group">
		    <label for="contentName" class="col-sm-2 control-label">Titel</label>
		    <div class="col-sm-4">
		    	<input type="hidden" name="id" class="form-control" id="contentId" value="@{{id}}">
				<input type="text" name="name" class="form-control" id="contentName" value="@{{name}}" required>
		    </div>
		    <label for="contentPermalink" class="col-sm-2 control-label">Permalink</label>
		    <div class="col-sm-4">
				<input type="text" name="permalink" class="form-control" id="contentPermalink" value="@{{permalink}}" readonly required>
		    </div>								    
	    </div>
	    <div class="form-group">
		    <label for="contentType" class="col-sm-2 control-label">Art des Inhaltes</label>
		    <div class="col-sm-4">
				<select class="form-control" name="type_id" id="contentTypeId">
					<option value="">- bitte wählen -</option>
					@{{#each contentTypes}}
					<option value="@{{id}}">@{{name}}</option>
			  		@{{/each}}
				</select>
		    </div>
		    <label for="contentAuthor" class="col-sm-2 control-label">Autor</label>
		    <div class="col-sm-4">
				<input type="text" name="created_by_name" class="form-control" id="contentAuthor" value="{{Auth::user()->name}}" required disabled>
		    </div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="is_published">Veröffentlicht</label>
			<div class="col-sm-4">
				<label class="radio-inline">
	  				<input type="radio" name="is_published" id="is_published_1" value="1" checked> Ja
				</label>
				<label class="radio-inline">
	  				<input type="radio" name="is_published" id="is_published_0" value="0"> Nein
				</label>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="published_at">Veröffentlichen am</label>
			<div class="col-sm-4">
				<input type="datetime-local" name="published_at" class="form-control" id="contentPublishedAt" value="@{{published_at}}">
			</div>
			<label class="col-sm-2 control-label" for="published_at">Zurücknehmen am</label>
			<div class="col-sm-4">
				<input type="datetime-local" name="unpublished_at" class="form-control" id="contentUnpublishedAt" value="@{{unpublished_at}}">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="content">Inhalt</label>
			<div class="col-sm-10">
				<textarea class="form-control" rows="6" name="content" id="contentContent" value="@{{content}}" placeholder="Inhalt Deines Beitrages"></textarea>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-4 col-sm-push-2">
				<button class="btn btn-success">Inhalt erstellen</button>
			</div>
		</div>
	</form>
	@{{/currentContent}}	
</div>
@stop

@section("content")
		<div class="container" id="flashContainer">
		</div>		
		<div class="container">
			<contents-app>
				@yield("appHeader")
				<ul class="nav nav-tabs" id="tabNav">
				  <li role="presentation" class="active">
				  	<a href="#public" aria-controls="blogControl" id="tabPublicControl">Inhalte</a></li>
				  <li role="presentation" id="controlProductCreate">
				  	<a href="#new" aria-controls="contentCreate" id="tabNewControl">Neuer Inhalt</a></li>
				</ul>
				<div class="tab-content">
					@yield("index")
					@yield("new")
				</div>
			</contents-app>
@stop