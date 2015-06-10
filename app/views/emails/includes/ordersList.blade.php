{{-- A list of orders that is shown in an email --}}
<ul>
	@foreach($orders as $order)
	<li>
		@include('emails.includes.order', array('order'=>$order))
	</li>
	@endforeach
</ul>