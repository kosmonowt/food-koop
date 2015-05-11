@extends('layouts.biokiste')
@include("includes.header")

@section("content")
	<div class="row">
		<div class="col-ms-12">
	  		<h2>Biokiste Login</h2>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				Bitte melde Dich an
			</div>
			<div class="panel-body">
				<form class="form-horizontal" method="POST" action="">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="newOrderFormOrderComment" class="col-sm-2 control-label">Benutername</label>
								<div class="col-sm-3">
									<input type="text" class="form-control" id="name" name="name">
								</div>
								<label for="newOrderFormOrderComment" class="col-sm-2 control-label">Passwort</label>
								<div class="col-sm-3">
									<input type="password" class="form-control" id="password" name="password">
								</div>						
								<div class="col-sm-2">
									<button class="btn btn-success" type="submit" id="newOrderFormButtonSubmit">Anmelden</button>
								</div>
							</div>					
						</div>							
					</div>
				</form>
			</div>
		</div>
	</div>
@stop