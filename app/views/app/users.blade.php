@extends('layouts.biokiste')
@include("includes.mustache")
@include("includes.bottomJs")
@include("includes.header")

@section("tabIndex")
<div role="tabpanel" class="tab-pane active" id="index">
	<div class="row">
		<div class="col-xs-12">
			<table class="table table-striped table-hover" data-model="Member">
				<tbody>
					<tr>
						<th></th>
						<th>Mitglieds- / Gruppenname</th>
						<th>Adresse &amp; Kontakt</th>
						<th>Dienstgruppe</th>
						<th>Mitglied Seit</th>
						<th>Kontostand</th>
						<th>Aktion</th>
					</tr>
					@{{#each members}}
					<tr class="">
						<td><input type="checkbox" can-value="complete"></td>
						<td>
							<div class="memberName">
								<span can-click="editAttr">@{{name}}</span>
						    	<span class="badge">@{{user.length}}</span>
					    	</div>
							<div class="memberNameForm" style="display:none;">
								<input type='text' class='form-control editValue' name="name" value='@{{name}}'>
								<button class='btn btn-success btn-xs' can-click='editSubmit'><span class="glyphicon glyphicon-ok"></span></button>
								<button class='btn btn-warning btn-xs' can-click='editAttr'><span class="glyphicon glyphicon-remove"></span></button>
							</div>
						    <div class="memberUsers" can-click="filterUsersByMember" onclick="$('a[href=\'#group\']').tab('show');">
						    	<small>
							    @{{#user}}
							    	<span class="userName" data-id="@{{id}}">
							    	@{{firstname}} @{{lastname}}
							    	</span>
							    @{{/each}}
							    @{{^user}}
							    	<span class="userName">User Hinzufügen.</span>
							    @{{/user}}
							    </small>
						    </div>
						</td>
						<td>
							<span>
								<div class="memberStreet">
									<span class="glyphicon glyphicon-home" title="Adresse"></span>
									<span can-click="editAttr">@{{street}}</span>,
						    	</div>
								<div class="memberStreetForm" style="display:none;">
									<input type='text' class='form-control editValue' name="street" value='@{{street}}'>
									<button class='btn btn-success btn-xs' can-click='editSubmit'><span class="glyphicon glyphicon-ok"></span></button>
									<button class='btn btn-warning btn-xs' can-click='editAttr'><span class="glyphicon glyphicon-remove"></span></button>
								</div>
							</span>
							<span>
								<span class="memberPlz">
									<span can-click="editAttr">@{{plz}}</span>
						    	</span>
								<div class="memberPlzForm" style="display:none;">
									<input type='text' class='form-control editValue' name="plz" value='@{{plz}}'>
									<button class='btn btn-success btn-xs' can-click='editSubmit'><span class="glyphicon glyphicon-ok"></span></button>
									<button class='btn btn-warning btn-xs' can-click='editAttr'><span class="glyphicon glyphicon-remove"></span></button>
								</div>												
							</span>
							<span>
								<span class="memberOrt">
									<span can-click="editAttr">@{{ort}}</span>
						    	</span>
								<div class="memberOrtForm" style="display:none;">
									<input type='text' class='form-control editValue' name="ort" value='@{{ort}}'>
									<button class='btn btn-success btn-xs' can-click='editSubmit'><span class="glyphicon glyphicon-ok"></span></button>
									<button class='btn btn-warning btn-xs' can-click='editAttr'><span class="glyphicon glyphicon-remove"></span></button>
								</div>												
							</span><br>
							<span>
								<span class="memberTelephone">
									<span class="glyphicon glyphicon-earphone" title="Telefonnummer"></span>
									<span can-click="editAttr">@{{telephone}}</span>
						    	</span>
								<div class="memberTelephoneForm" style="display:none;">
									<input type='text' class='form-control editValue' name="telephone" value='@{{telephone}}'>
									<button class='btn btn-success btn-xs' can-click='editSubmit'><span class="glyphicon glyphicon-ok"></span></button>
									<button class='btn btn-warning btn-xs' can-click='editAttr'><span class="glyphicon glyphicon-remove"></span></button>
								</div>												
							</span>											
							<br>
							<span>
								<span class="memberEmail">
									<a href="mailto:@{{email}}" class="glyphicon glyphicon-envelope" title="Email Schreiben"></a>&nbsp;
									<span can-click="editAttr">@{{email}}</span>
						    	</span>
								<div class="memberEmailForm" style="display:none;">
									<input type='text' class='form-control editValue' name="email" value='@{{email}}'>
									<button class='btn btn-success btn-xs' can-click='editSubmit'><span class="glyphicon glyphicon-ok"></span></button>
									<button class='btn btn-warning btn-xs' can-click='editAttr'><span class="glyphicon glyphicon-remove"></span></button>
								</div>
							</span>
						</td>
						<td>
							<div class="memberMemberGroupName can-id" onclick="$(this).children('.statusSetter').toggle();" data-id="@{{id}}" data-model="member_group">
								@{{member_group.name}}
								<div class='statusSetter' style="display:none;">
									@{{#each memberGroups}}
										<input type="hidden" class="editValue" name="member_group_id" value="@{{id}}">
										<div class='setStatus' onclick="$(this).parent('.statusSetter').toggle();" can-click="editSubmit" data-scope="members">@{{name}}</div>
									@{{/each}}
							</div>											
						</td>
				        <td>
							<div class="memberDoe">
								<span can-click="editAttr">@{{dmY data=date_of_entry field="date_of_entry"}}</span>
					    	</div>
							<div class="memberDoeForm" style="display:none;">
								<input type='date' class='form-control editValue' name="date_of_entry" value='@{{date_of_entry}}'>
								<button class='btn btn-success btn-xs' can-click='editSubmit'><span class="glyphicon glyphicon-ok"></span></button>
								<button class='btn btn-warning btn-xs' can-click='editAttr'><span class="glyphicon glyphicon-remove"></span></button>
							</div>									        
				        </td>
				        <td>
				        	<div class="memberBalance">
				        		<span>xx,xx&euro;</span><br>
				        		<button class="btn btn-info btn-xs" title="Kontoübersicht" can-click="openLedger"><span class="glyphicon glyphicon-piggy-bank"></span></button>
				        		<button class="btn btn-success btn-xs" title="Schnellbuchung" can-click="quickLedgerTransaction"><span class="glyphicon glyphicon-transfer"></span></button>
				        	</div>
				        </td>
					    <td>
					    	<br><button class="btn btn-danger btn-xs" can-click="delete"><span class="glyphicon glyphicon-remove" title="Löschen"></span></button>
					    </td>
				    </tr>
				    @{{/each}}
			    </tbody>
			</table>
		</div>
	</div>
</div>
@stop

@section("tabLedger")
<div role="tabpanel" class="tab-pane" id="ledger">
	<div class="row">
		<div class="col-ms-6"><h3>Kontoübersicht für @{{currentMember.name}}</h3></div>
	</div>
	<div class="col-xs-12">
		<form action="">
			<table class="table table-striped table-hover" data-model="Member">
				<tbody>
					<tr>
						<th>Datum</th>
						<th>Verwendungszweck</th>
						<th colspan="2">Betrag</th>
					</tr>
					@{{#each currentLedger}}
					<tr>
						<td><samp>@{{date}}</samp></td>
						<td colspan="2"><samp>@{{vwz}}</samp></td>
						<td class="text-right @{{posNeg data=betrag}}"><samp>@{{balance}}&euro;</samp></td>
					</tr>
					@{{/each}}
					<tr>
						<td colspan="2"><strong><samp>Kontostand:</samp></strong></td>
						<td colspan="2"class="text-right"><strong><samp>@{{currentMember.balance}}&euro;</samp></strong></td>
					</tr>
					<tr>
						<td><input type="date" class="form-control input-sm" name="memberLedgerDate" id="memberLedgerDate" value=""></td>
						<td><input type="text" class="form-control input-sm" name="memberLedgerVwz" id="memberLedgerVwz" value="Einzahlung"></td>
						<td>
							<div class="input-group input-group-sm">
								<input type="number" class="form-control" name="memberLedgerBalance" id="memberLedgerBalance" step="0.01" can-keyup="checkBalanceValue">
								<span class="input-group-addon">&euro;</span>
							</div>
						</td>
						<td>
							<button class="btn btn-success btn-xs" name="memberLedgerAdd" id="memberLedgerAdd" can-click="submitLedgerTransaction"><span class="glyphicon glyphicon glyphicon-check"></span></button>
							<button class="btn btn-warning btn-xs" type="reset"><span class="glyphicon glyphicon-remove"></span></button>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
@stop

@section("tabCreate")
<div role="tabpanel" class="tab-pane" id="create">
	<form class="form-horizontal" action="" can-submit="submitMember">
		<div class="row">
			<div class="col-ms-6">
				<h3>Neue Bestellgruppe</h3>
			</div>
			<div class="col-ms-6">

			</div>							
		</div>
		<div class="form-group">
		    <label for="newMemberFormName" class="col-sm-2 control-label">Gruppen- / Mitgliedsname</label>
		    <div class="col-sm-4">
				<input type="text" name="name" class="form-control" id="newMemberFormName" value="" required>
		    </div>
		    <label for="newMemberFormDateOfEntry" class="col-sm-2 control-label">Beitrittsdatum</label>
		    <div class="col-sm-4">
				<input type="date" name="date_of_entry" class="form-control" id="newMemberFormDateOfEntry" value="" required>
		    </div>								    
	    </div>
	    <div class="form-group">
		    <label for="newMemberFormStreet" class="col-sm-2 control-label">Straße</label>
		    <div class="col-sm-4">
				<input type="text" name="street" class="form-control" id="newMemberFormStreet" value="" required>
		    </div>
		    <label for="newMemberFormPlz" class="col-sm-2 control-label">Postleitzahl / Ort</label>
		    <div class="col-sm-2">
				<input type="text" name="plz" class="form-control" id="newMemberFormPlz" value="" required validate="\d{5}">
		    </div>
		    <div class="col-sm-2">
				<input type="text" name="ort" class="form-control" id="newMemberFormOrt" value="" default="Leipzig" required>
		    </div>				
		</div>
		<div class="form-group">			    
		    <label for="newMemberFormTelephone" class="col-sm-2 control-label">Telefonnummer</label>
		    <div class="col-sm-4">
				<input type="tel" name="telephone" class="form-control" id="newMemberFormTelephone" value="">
		    </div>
		    <label for="newMemberFormEmail" class="col-sm-2 control-label">E-Mail</label>
		    <div class="col-sm-4">
				<input type="email" name="email" class="form-control" id="newMemberFormEmail" value="">
		    </div>					    
		</div>
		<div class="form-group">
		    <label for="newMemberFormMemberGroup" class="col-sm-2 control-label">Dienstgruppe</label>
		    <div class="col-sm-4">
				<select class="form-control" name="member_group_id" id="newMemberFormMemberGroup">
					@{{#each memberGroups}}
					<option value="@{{id}}">@{{name}}</option>
			  		@{{/each}}
				</select>
		    </div>										
		    <label for="newMemberFormMemberGroup" class="col-sm-2 control-label">Mitgliedsstatus</label>
		    <div class="col-sm-4">
				<select class="form-control" name="member_status_id" id="newMemberFormMemberStatus">
					@{{#each memberStatus}}
					<option value="@{{id}}">@{{name}}</option>
			  		@{{/each}}
				</select>
		    </div>																		    
		</div>
		<div class="form-group">
			<label for="initialLedger" class="col-sm-2 control-label">Einlage / Darlehen</label>
			<div class="col-sm-4">
				<input class="form-control" type="number" name="initialLedger" id="newMemberFormInitialLedger" min="5" step="1" value="50">
			</div>
			<div class="col-sm-4 col-sm-push-2">
				<button class="btn btn-success">Bestellgruppe anlegen</button>
			</div>
		</div>
	</form>
</div>
@stop

@section("tabUsers")
<div role="tabpanel" class="tab-pane modelSection" id="group" data-model="User">
	<div class="row">
		<div class="col-xs-12">
			<table class="table table-striped table-hover">
				<tbody>
					<tr>
						<th></th>
						<th>Vorname</th>
						<th>Nachname</th>
						<th>Login / Benutzername</th>
						<th>Passwort</th>
						<th>E-Mail</th>
						<th>Telefon</th>
						<th>Letzter Login</th>
						<th>Usergruppe</th>
						<th>Aktion</th>
					</tr>
					@{{#each memberUsers}}
					<tr>
						<td><input type="checkbox" can-value="complete" name="id" value="@{{id}}"></td>
						<td>
							<div class="userFirstname">
								<span can-click="editAttr">@{{firstname}}</span>
					    	</div>
							<div class="userFirstnameForm" style="display:none;">
								<input type='text' class='form-control input-sm editValue' name="firstname" value='@{{firstname}}'>
								<button class='btn btn-success btn-xs' can-click='editSubmit'><span class="glyphicon glyphicon-ok"></span></button>
								<button class='btn btn-warning btn-xs' can-click='editAttr'><span class="glyphicon glyphicon-remove"></span></button>
							</div>
						</td>
						<td>
							<div class="userLastname">
								<span can-click="editAttr">@{{lastname}}</span>
					    	</div>
							<div class="userLastnameForm" style="display:none;">
								<input type='text' class='form-control input-sm editValue' name="lastname" value='@{{lastname}}'>
								<button class='btn btn-success btn-xs' can-click='editSubmit'><span class="glyphicon glyphicon-ok"></span></button>
								<button class='btn btn-warning btn-xs' can-click='editAttr'><span class="glyphicon glyphicon-remove"></span></button>
							</div>
						</td>
						<td>
							<div class="userUsername">
								<span can-click="editAttr">@{{username}}</span>
					    	</div>
							<div class="userUsernameForm" style="display:none;">
								<input type='text' class='form-control input-sm editValue' name="username" value='@{{username}}'>
								<button class='btn btn-success btn-xs' can-click='editSubmit'><span class="glyphicon glyphicon-ok"></span></button>
								<button class='btn btn-warning btn-xs' can-click='editAttr'><span class="glyphicon glyphicon-remove"></span></button>
							</div>
						</td>
						<td>
							<div class="userPassword">
								<span can-click="editAttr">Neu setzen</span>
					    	</div>
							<div class="userPasswordForm" style="display:none;">
								<input type='text' class='form-control input-sm editValue' name="password" value=''>
								<button class='btn btn-success btn-xs' can-click='editSubmit'><span class="glyphicon glyphicon-ok"></span></button>
								<button class='btn btn-warning btn-xs' can-click='editAttr'><span class="glyphicon glyphicon-remove"></span></button>
							</div>
						</td>
						<td>
							<div class="userEmail">
								<span can-click="editAttr">@{{email}}</span>
					    	</div>
							<div class="userEmailForm" style="display:none;">
								<input type='text' class='form-control input-sm editValue' name="email" value='@{{email}}'>
								<button class='btn btn-success btn-xs' can-click='editSubmit'><span class="glyphicon glyphicon-ok"></span></button>
								<button class='btn btn-warning btn-xs' can-click='editAttr'><span class="glyphicon glyphicon-remove"></span></button>
							</div>
						</td>
						<td>
							<div class="userTelephone">
								<span can-click="editAttr">@{{telephone}}</span>
					    	</div>
							<div class="userTelephoneForm" style="display:none;">
								<input type='text' class='form-control input-sm editValue' name="telephone" value='@{{telephone}}'>
								<button class='btn btn-success btn-xs' can-click='editSubmit'><span class="glyphicon glyphicon-ok"></span></button>
								<button class='btn btn-warning btn-xs' can-click='editAttr'><span class="glyphicon glyphicon-remove"></span></button>
							</div>
						</td>
						<td>
							<div class="userLastLogin">
								<span>@{{last_login}}</span>
					    	</div>
						</td>
						<td>
							<div class="userUserGroup">@{{userGroup}}</div>
						</td>
					    <td><button class="btn btn-danger btn-sm" can-click="delete"><span class="glyphicon glyphicon-remove" title="Löschen"></span></button></td>
				    </tr>
				    @{{/each}}
					<tr id="userCreateForm">
						<td><input type="checkbox" can-value="complete"></td>
						<td>
							<input type='hidden' name="member_id" value="@{{currentMember.id}}">
							<input type='text' class='form-control input-sm' name="firstname" placeholder='vorname' value=''>
						</td>
						<td>
							<input type='text' class='form-control input-sm' name="lastname" placeholder='nachname' value=''>
						</td>
						<td>
							<input type='text' class='form-control input-sm' name="username" placeholder='loginname' value=''>
						</td>
						<td>
							<input type='text' class='form-control input-sm' name="password" placeholder='password' value=''>
						</td>
						<td>
							<input type='text' class='form-control input-sm' name="email" placeholder='email' value=''>
						</td>
						<td>
							<input type='text' class='form-control input-sm' name="telephone" placeholder="telephone" value=''>
						</td>
						<td colspan="2">
							<select class="form-control input-sm" name="user_group_id">
							@{{#each userGroups}}
								<option value="@{{id}}">@{{name}}</option>
							@{{/each}}
							</select>
						</td>
					    <td><button class="btn btn-success btn-sm" can-click="userCreate"><span class="glyphicon glyphicon-ok" title="Hinzufügen"></span></button></td>
				    </tr>				    
			    </tbody>
			</table>
		</div>
	</div>
</div>
@stop

@section("content")
			<members-app>
				@yield("appHeader")
				<ul class="nav nav-tabs" id="tabNav">
				  <li role="presentation" class="active"><a href="#index" aria-controls="index" id="tabIndexControl">Übersicht</a></li>
				  <li role="presentation"><a href="#create" aria-controls="create">Neue Bestellgruppe</a></li>
				  <li role="presentation"><a href="#group" aria-controls="group">Gruppenmitglieder</a></li>
				</ul>
				<div class="tab-content">
					@yield("tabIndex")
					@yield("tabCreate")
					@yield("tabLedger")
					@yield("tabUsers")
				</div>
			</members-app>
@stop