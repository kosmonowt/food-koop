@section("header")
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<div class="navbar-header">
			  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
			    <span class="sr-only">Toggle navigation</span>
			    <span class="icon-bar"></span>
			    <span class="icon-bar"></span>
			    <span class="icon-bar"></span>
			  </button>
			  <a class="navbar-brand" href="#">MyBiokiste</a>
			</div>
			<ul class="nav navbar-nav">
				<li class="@if ($controller == 'dashboard') active @endif"><a href="dashboard.html">Dashboard <span class="sr-only">(current)</span></a></li>
				<li class="@if ($controller == 'orders') active @endif"><a href="orders.html">Bestellungen</a></li>
				<li class="@if ($controller == 'shifts') active @endif"><a href="tasks.html">Dienstplan</a></li>
				<li class="@if ($controller == 'users') active @endif"><a href="users.html">Mitglieder</a></li>
			</ul>
			<ul class="nav navbar-nav pull-right">
				<li><a href="logout">Logout</a></li>
			</ul>
		</div>
	</nav>
@stop

@section("appHeader")
	<div class="row">
		<div class="col-ms-12">
	  		<h2>
	  		@if (!isset($controller))
  				MyBiokiste
	  		@elseif ($controller == "users")
	  			Mitglieder
	  		@elseif ($controller == "orders")
	  			Bestellungen
  			@else
  				{{{$controller}}}
  			@endif
	  		</h2>
		</div>
	</div>
@stop