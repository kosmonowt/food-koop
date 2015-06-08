{{-- A list of orders that is shown in an email --}}
<ul>
	@foreach($orders as $order)
	<li>
		<span class="orderAmount">{{$order['amount']}} {{$order['product']['unit_unit']}}</span> <strong>{{$order['product']['sku']}}, {{$order['product']['name']}}</strong> zu <span class="orderPdice">{{$order['product']['price']}} {{$order['product']['singleRetailPrice']}}€</span>
	</li>
	@endforeach
</ul>