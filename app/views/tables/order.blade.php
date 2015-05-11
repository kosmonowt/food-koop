<table>
	<tr>
		<td><h1>Bestellungen {{$merchant->name}}</h1></td>
	</tr>
	<tr>
		<td><strong>Datum:</strong> {{ date("d.m.Y H:i:s") }}</td>
	</tr>
	<tr>
	</tr>
	<tr>
		<td></td>
		<th>Artikel Nr.</th>
		<th>Name</th>
		<th>Gebindeart</th>
		<th>Bestellmenge</th>
		<th>Angenommener Listenpreis</th>
		<th>Geschätzter Gesamtpreis</th>
	</tr>
	@foreach($orders as $order)
	<tr>
		<td></td>
		<td>{{$order->sku}}</td>
		<td>{{$order->name}}</td>
		<td>{{$order->unit_unit}}</td>
		<td>{{$order->bulkToOrder}}</td>
		<td>{{str_replace(".",",",$order->price)}}</td>
		<td>{{str_replace(".",",",$order->totalForBulk)}}</td>
	</tr>
	@endforeach
</table>