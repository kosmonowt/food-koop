@extends('layouts.biokiste')
@include("includes.mustache")
@include("includes.bottomJs")
@include("includes.header")

@section("tabTasks")
<div role="tabpanel" class="tab-pane modelSection active" id="tabTasks" data-model="Tasks">
	<div class="row">
		<div class="col-sm-6">
			<h3>Dienstplan</h3>
		</div>
	</div>
	<div class="row">
		@{{#each weekList}}
		<div class="col-sm-6 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading">Woche @{{number}}/@{{year}}</div>
		  		<div class="panel-body">
		  			<div class="list-group">
		  				@{{#each days}}
						<div href="#" class="list-group-item">
							<h4 class="list-group-item-heading">@{{dowName data=day_of_week}}, @{{dmY data=date field="date"}}</h4>
							@{{#each task}}
							<p class="list-group-item-text">@{{timeHI data=start field="start"}} - @{{timeHI data=stop field="stop"}} @{{task_type.name}}</p>
							@{{/each}}
						</div>
		  				@{{/each}}
					</div>
		  		</div>
			</div>
		</div>
		@{{/each}}
	</div>	
</div>
@stop

@section("tabTaskTypes")
<div role="tabpanel" class="tab-pane modelSection" id="tabTaskTypes" data-model="TaskTypes">
	<div class="row">
		<div class="col-sm-6">
			<h3>Übersicht der Dienste</h3>
		</div>
	</div>
	<form>
		<div class="row">
			<div class="col-xs-12">
				<table class="table table-striped table-hover">
					<tbody>
						<tr>
							<th></th>
							<th>Name / Beschreibung</th>
							<th>Wochentag / Rhythmus</th>
							<th>Von / bis</th>
							<th>Aktiv seit / bis</th>
							<th>Dienstgruppe</th>
							<th>Aktiv</th>
							<th>Aktion</th>
						</tr>
						@{{#each taskTypes}}
						<tr>
							<td>
								<input type="checkbox" can-value="complete" name="id" value="@{{id}}">
							</td>
							<td>
								<strong>@{{name}}</strong><br>
								@{{short_description}}
							</td>
							<td>
								@{{dowName data=day_of_week}}, @{{taskRhythm data=repeat_days}}
							</td>
							<td>
								@{{timeHI data=time_start field="time_stop"}} - @{{timeHI data=time_stop field="time_stop"}}
							</td>
							<td>
								@{{publishedStartStop data=published_start}}
							</td>
							<td>
								@{{member_group.name}}
							</td>
							<td>
								@{{#if active}}
									<button class="btn btn-success btn-sm"><span class="glyphicon glyphicon-play"></span></button>
								@{{else}}
									<button class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-pause"></span></button>
								@{{/if}}
							</td>
						    <td><button class="btn btn-danger btn-sm" can-click="deleteTaskType" data-id="@{{id}}"><span class="glyphicon glyphicon-remove" title="Löschen"></span></button></td>
					    </tr>
					    @{{/each}}
				    </tbody>
				</table>
			</div>
		</div>
	</form>
	<div class="row">
		<div class="col-sm-6">
			<a class="btn btn-success" can-click="openNewTaskTab">Neuen Dienst erstellen</a>
		</div>
	</div>
</div>
@stop

@section("tabTaskTypeEditor")
<div role="tabpanel" class="tab-pane modelSection" id="taskTypeEditor" data-model="TaskType">
	<form can-submit="saveTaskType">
		<div class="row">
			<h3>@{{taskTypeEditorCaption}}</h3>
		</div>
		<div class="row">
			<label class="control-label col-sm-2">Name</label>
			<div class="col-sm-4">
				<input type="hidden" name="id" value="@{{taskType.id}}">
				<input type="text" class="form-control" name="name" value="@{{taskType.name}}" placeholder="Einfach und Kurz">
			</div>
			<label class="control-label col-sm-2">Kurzbeschreibung</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" name="short_description" value="@{{taskType.short_description}}" placeholder="Kurzbeschreibung">
			</div>
		</div>
		<div class="row">
			<label class="control-label col-sm-12">Ausführliche Beschreibung, Anleitung etc.</label>
			<div class="col-sm-12">
				<textarea class="form-control" rows="5" name="long_description" value="@{{taskType.long_description}}" placeholder="Ausführlichcher Text, vielleicht sogar ein Tutorial?"></textarea>
			</div>
		</div>
		<div class="row">
			<label class="control-label col-sm-2 col-xs-6">Wochentag</label>
			<div class="col-sm-2 col-xs-6">
				<select class="form-control" name="day_of_week" id="taskTypeDayOfWeek">
					<option value="">wählen</option>
					<option value="1">Montag</option>
					<option value="2">Dienstag</option>
					<option value="3">Mittwoch</option>
					<option value="4">Donnerstag</option>
					<option value="5">Freitag</option>
					<option value="6">Samstag</option>
					<option value="7">Sonntag</option>
				</select>
			</div>
			<label class="control-label col-sm-2 col-xs-6">Wiederholung</label>
			<div class="col-sm-2 col-xs-6">
				<select class="form-control" name="repeat_days" id="taskTypeRepeatDays">
					<option value="">wählen</option>
					<option value="1w">Wöchentlich</option>
					<option value="2w">Alle 2 Wochen</option>
					<option value="3w">Alle 3 Wochen</option>
					<option value="4w">Alle 4 Wochen</option>
					<option value="1x">jeden 1. im Monat</option>
					<option value="2x">jeden 2. im Monat</option>
					<option value="3x">jeden 3. im Monat</option>
					<option value="4x">jeden 4. im Monat</option>
				</select>
			</div>
			<div class="col-sm-4 col-xs-12 checkbox">
				<label>
					<input type="checkbox" name="active" id="taskTypeActive" @{{#if taskType.active}}checked="checked"@{{/if}}>
					Aktiviert
				</label>
			</div>
		</div>
		<div class="row">
			<label class="control-label col-sm-2">Startzeit</label>
			<div class="col-sm-4">
				<input class="form-control" type="time" name="time_start" value="@{{taskType.time_start}}">
			</div>
			<label class="control-label col-sm-2">Endzeit</label>
			<div class="col-sm-4">
				<input class="form-control" type="time" name="time_stop" value="@{{taskType.time_stop}}">
			</div>		
		</div>
		<div class="row">
			<label class="control-label col-sm-2">Aktiv von</label>
			<div class="col-sm-4">
				<input class="form-control" type="date" name="published_start" value="@{{taskType.published_start}}">
			</div>
			<label class="control-label col-sm-2">Aktiv bis</label>
			<div class="col-sm-4">
				<input class="form-control" type="date" name="published_stop" value="@{{taskType.published_stop}}">
			</div>		
		</div>
		<div class="row">
			<label class="control-label col-sm-2">Dienstgruppe</label>
			<div class="col-sm-4">
				<select class="form-control" name="member_group_id" id="taskTypeMemberGroupId">
					<option value="">wählen</option>
					@{{#each memberGroups}}
						<option value="@{{id}}">@{{name}}</option>
					@{{/each}}
				</select>
			</div>
			<div class="col-sm-6">
				<small>Diese Dienstgruppe erhält bevorzugt Informationen zu offenen Diensten etc.</small>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4 col-sm-offset-2">
				<button class="btn btn-success" type="submit">Speichern</button>
			</div>
		</div>
	</form>
</div>
@stop

@section("content")
			<members-app>
				@yield("appHeader")
				<ul class="nav nav-tabs" id="tabNav">
				  <li role="presentation" class="active"><a href="#tabTasks" aria-controls="index" id="tabTasksControl">Dienstplan</a></li>
				  <li role="presentation"><a href="#tabTaskTypes" aria-controls="index" id="tabTaskTypesControl">Dienstarten</a></li>
				</ul>
				<div class="tab-content">
					@yield("tabTasks")
					@yield("tabTaskTypes")
					@yield("tabTaskTypeEditor")
				</div>
			</members-app>
@stop