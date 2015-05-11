<table>
	<tr>
		<td colspan="6"><h1>Bestellungen {{$merchant->name}}</h1></td>
	</tr>
	<tr>
		<td colspan="6"><h2>Liste f&uuml;r den Auspackdienst</h2></td>
	</tr>
	<tr>
		<td colspan="6"><strong>Datum:</strong> {{ date("d.m.Y H:i:s") }}</td>
	</tr>
	<tr>
		<th>Bestellgruppe</th>
		<th>Artikel Nr.</th>
		<th>Name</th>
		<th>Abnahme</th>
		<th>Einheit</th>
		<th>Gebindegr&ouml;&szlig;e</th>
		<th>Einzelpreis</th>
		<th>wenn abweichend</th>
		<th>MwSt.</th>
		<th>Preis inkl. MwSt.</th>
		<th>wenn abweichend</th>
	</tr>
	@foreach($orders as $order)
	<tr>
		<td>{{$order->member->name}}
		<td>{{$order->product->sku}}</td>
		<td>{{$order->product->name}}</td>
		<td>{{$order->amount}}</td>
		<td>{{$order->product->units}}</td>
		<td>{{$order->product->unit_unit}}</td>
		<td>{{str_replace(".",",",$order->product->price)}}</td>
		<td></td>
		<td>{{$order->product->taxrate}}%</td>
		<td>{{(1 + $order->product->taxrate) * $order->product->price}}</td>
		<td></td>
	</tr>
	@endforeach
</table>