@extends('layouts.biokiste')
@include("includes.header")
@include("dashboard.commons")
@include("dashboard.admin")

@section('content')
<dashboard-app>
	<div class="row">
		@yield("latestNews")
	</div>
	<div class="row">
		@yield("myOrders")
		@yield("marketplace")
	</div>
	<div class="row">
		@yield("myShifts")
		@yield("upcomingShifts")
	</div>
	<div class="row">
		@yield("myProfile")		
		@yield("myLedger")
	</div>
</dashboard-app>
@stop

@section("bottomJs")
	<script type="text/javascript" src="js/dashboard.js"></script>
@stop