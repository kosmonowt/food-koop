@include("includes.mustache")

@section("latestNews")
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<strong>Aktuelles</strong>
				</div>
				<div class="panel-body">
					@foreach($articles as $article)
						<article class="article" id="article_@{{id}}">
							<h4>{{$article->name}}</h4>
							<p><small>Geschrieben am {{$article->created_at}} von {{$article->author}}</small></p>
								{!!$article->parsedContent!!}
						</article>
					@endforeach
				</div>
			</div>
		</div>
@stop

@section("myOrders")
		<div class="col-sm-6">
			<div class="panel panel-default dashboard-sized">
				<div class="panel-heading">
					<strong>Meine aktuellen Bestellungen</strong><span style="float:right;text-align:right;"><a href="orders.html#tab=order"><small>Neue Bestellung</small></a></span>
				</div>
				<div class="scrollable">
					<table class="table table-striped table-condensed">
						<tbody>
							<th colspan="3">Übersicht</th>
							<th colspan="3">Total <small>inkl. MwSt.</small></th>
						@{{#each myOrders}}
						<tr>
							<td>
								<div class="statusIcon status-@{{order_state_id}}"></div>
							</td>
							<td>
								@{{amount}}x
							</td>
							<td>
								@{{product.name}} (@{{product.weight_per_unit}}@{{product.tare_unit}})
							</td>
							<td>
								@{{orderPrice data=product}}€
							</td>
							<td>
								<small>(@{{productPrice data=product}}€/@{{product.unit_unit}})</small>
							</td>
							<td>
								@{{#if isUserDeleteable}}
								<form class="form-inline">
									<button class="btn btn-danger btn-xs" type="button" can-click="deleteMyOrder"><span class="glyphicon glyphicon-remove-sign"></span></button>
								</form>
								@{{/if}}
							</td>
						</tr>
						@{{/each}}
						</tbody>
					</table>
				</div>
			</div>
		</div>
@stop

@section("marketplace")
		<div class="col-sm-6">
			<div class="panel panel-default dashboard-sized">
				<div class="panel-heading">
					<strong>Marktplatz</strong>
				</div>
				<div class="scrollable">
					<table class="table table-striped table-condensed">
						<tbody>
							<th>Verfügbar</th>
							<th>Produkt</th>
							<th colspan="2">Preis <small>(inkl. MwST)</small></th>
						@{{#each marketplace}}
						<tr>
							<td>
								@{{availableAmount}} / @{{units}}
							</td>
							<td>
								@{{name}} (@{{weight_per_unit}}@{{tare_unit}})
							</td>
							<td>
								<span class="text-nowrap">@{{marketplacePrice data=price}} €</span>
							</td>
							<td>
								<form class="form-inline" can-submit="marketplaceOrder">
									<div class="input-group input-group-sm">
										<input type="number" min="1" class="form-control" placeholder="anzahl" name="amount">
										<input type="hidden" name="product_id" value="@{{id}}">
										<input type="hidden" name="merchant_id" value="@{{merchant_id}}">
										<span class="input-group-addon">@{{unit_unit}}</span>
										<span class="input-group-btn">
											<button class="btn btn-success" type="submit"><span class="glyphicon glyphicon-shopping-cart"></span></button>
										</span>
									</div>
								</form>
	  						</td>
						</tr>
						@{{/each}}
						</tbody>
					</table>
				</div>
			</div>
		</div>
@stop

@section("myShifts")
		<div class="col-sm-6">
			<div class="panel panel-default dashboard-sized">
				<div class="panel-heading">
					<strong>Dienste, für die Du eingetragen bist</strong>
				</div>
					<ul class="list-group scrollable">
					@{{#each myTasks}}
						<li class="list-group-item">
							<strong>@{{task_type.name}} am @{{dmY data=date field="date"}}</strong> von @{{timeHI data=date field="start"}}Uhr bis @{{timeHI data=date field="stop"}}Uhr
							<button class="btn btn-danger btn-xs pull-right" can-click="myTaskUndo"><span class="glyphicon glyphicon-remove-sign"></span></button>
						</li>
					@{{/each}}
					</ul>
			</div>
		</div>
@stop

@section("upcomingShifts")
		<div class="col-sm-6">
			<div class="panel panel-default dashboard-sized">
				<div class="panel-heading">
					<strong>Offene Dienste</strong>
				</div>
				<ul class="list-group scrollable">
				@{{#each upcomingTasks}}
					<li class="list-group-item">
						<strong>@{{task_type.name}} am @{{dmY data=date field="date"}}</strong> von @{{timeHI data=date field="start"}}Uhr bis @{{timeHI data=date field="stop"}}Uhr
						<div class="btn-group btn-group-xs pull-right" role="group" aria-label="Dienst Bearbeiten">
							<button class="btn btn-success btn-xs" can-click="upcomingTaskAssign"><span class="glyphicon glyphicon-log-in"></span></button>
							<button class="btn btn-info btn-xs"><span class="glyphicon glyphicon-info-sign"></span></button>
						</div>
					</li>
				@{{/each}}
				</ul>
			</div>
		</div>
@stop

@section("myProfile")
		<div class="col-sm-6">
			<div class="panel panel-default dashboard-sized">
				<div class="panel-heading">
					<strong>Bestellgruppe / Benutzer</strong>
				</div>
				<div class="panel-body">
					<p>
						Bestellgruppe: <strong>{{{$member->name}}}</strong>, {{{$member->street}}} {{{$member->plz}}} {{{$member->ort}}}<br>
						Gruppenmitglied: <strong>{{{$user->firstname}}} {{{$user->lastname}}}</strong>, {{{$user->username}}}, @if (strlen($user->email)) {{{$user->email}}}@else <em>Email Adresse einragen</em>@endif, @if (strlen($user->telephone)) {{{$user->telephone}}}@else <em>Telefonnummer einragen</em>@endif<br>
					</p>
				</div>
			</div>
		</div>
@stop

@section("myLedger")
		<div class="col-sm-6">
			<div class="panel panel-default dashboard-sized">
				<div class="panel-heading">
					<strong>Kontoauszug</strong>
				</div>
				<div class="scrollable">
					<table class="table table-striped table-condensed">
						<tbody>
							<tr>
								<th><samp>Datum</samp></th>
								<th><samp>Betrag</samp></th>
								<th><samp>VWZ</samp></th>
							</tr>
							@foreach($ledger as $transaction)
							<tr>
								<td><samp><small>{{$transaction->date}}</small></samp></td>
								<td class="@if($transaction->balance < 0)text-danger @else text-success @endif"><samp><small><strong>{{number_format($transaction->balance,2,",",".")}}&euro;</strong></small></samp></td>
								<td><samp><small>{{$transaction->vwz}}</small></samp></td>
							</tr>
							@endforeach
							<tr class="info">
								<td><samp><small><strong>Kontostand</strong></small></samp></td>
								<td class="@if($kontostand < 0)text-danger @else text-success @endif"><samp><small><strong>{{number_format($kontostand,2,",",".")}}&euro;</strong></small></samp></td>
								<td><samp><small>{{date("d.m.Y")}}</small></samp></td>
							</tr>
							<tr>
								<td><samp><small>Starteinlage</small></samp></td>
								<td><samp><small>{{number_format($starteinlage,2,",",".")}}&euro;</small></samp></td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
@stop