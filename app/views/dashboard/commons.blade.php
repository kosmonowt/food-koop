@section("latestNews")
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<strong>Aktuelles</strong>
				</div>
				<div class="panel-body">
					### AKTUELLES ###
				</div>
			</div>
		</div>
@stop

@section("myOrders")
		<div class="col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<strong>Meine aktuellen Bestellungen</strong><br><a href="orders.html#tab=order"><small>Neue Bestellung</small></a>
				</div>
				<div class="panel-body">
					@if (count($myOrders))
					<table class="table table-striped table-condensed">
						<tbody>
							<th colspan="3">Übersicht</th>
							<th colspan="2">Total <small>inkl. MwSt.</small></th>
						@foreach ($myOrders as $order)
						<tr>
							<td>
								<div class="statusIcon status-{{$order->order_state_id}}"></div>
							</td>
							<td>
								{{$order->amount}}x
							</td>
							<td>
								{{$order->product->name}}
							</td>
							<td>
								{{round($order->amount * $order->product->price * (1+ ($order->product->taxrate/100)),2)}}€
							</td>
							<td>
								<small>({{round($order->product->price * (1+ ($order->product->taxrate/100)),2)}}€)</small>
							</td>
						</tr>
						@endforeach
						</tbody>
					</table>
					@else
					<p>Derzeit keine Bestellungen
					</p>
					@endif
				</div>
			</div>
		</div>
@stop

@section("marketplace")
		<div class="col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<strong>Marktplatz</strong>
				</div>
				<div class="panel-body">
					@if (count($marketplace))
					<table class="table table-striped table-condensed">
						<tbody>
							<th>Verfügbar</th>
							<th>Produkt</th>
							<th>Preis <small>(inkl. MwST)</small></th>
						@foreach ($marketplace as $order)
						<tr>
							<td>
								{{($order->units - $order->remainingAmount)}} / {{$order->units}}
							</td>
							<td>
								{{$order->name}}
							</td>
							<td>
								{{round($order->price * (1+ ($order->product_type->tax / 100)),2)}} €
							</td>
						</tr>
						@endforeach
						</tbody>
					</table>
					@else
					<p>Keine Bestellung zum Vervollständigen<br>
					</p>
					@endif

				</div>
			</div>
		</div>
@stop

@section("myShifts")
		<div class="col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<strong>Dienste</strong>
				</div>
				<div class="panel-body">
					## HIER MEINE DIENSTE ##
				</div>
			</div>
		</div>
@stop

@section("upcomingShifts")
		<div class="col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<strong>Kommende Dienste</strong>
				</div>
				<div class="panel-body">
					## Hier Dienstplan für kommende Woche ##
				</div>
			</div>
		</div>
@stop

@section("myProfile")
		<div class="col-sm-6">
			<div class="panel panel-default">
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
			<div class="panel panel-default">
				<div class="panel-heading">
					<strong>Kontoauszug</strong>
				</div>
				<div class="panel-body">
					<table class="table">
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