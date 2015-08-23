@extends('layouts.biokiste')
@include('includes.mustache')
@include("includes.bottomJs")
@include("includes.header")

@section("tabIndex")
<div role="tabpanel" class="tab-pane active" id="index">
	<div class="row">
		<div class="col-xs-12">
			<br>
			<ul class="nav nav-pills" id="orderFilterButtons">
				<li role="presentation"><a href="#" class="tabToggler active" can-click="showOrdersByProduct" data-value="waiting" data-shows="ordersByProductTable" data-affects="orderToggleTables">Wartend</a></li>
				<li role="presentation"><a href="#" class="tabToggler" can-click="showOrdersByProduct" data-value="listed" data-shows="ordersByProductTable" data-affects="orderToggleTables">Bestelliste</a></li>				
				<li role="presentation"><a href="#" class="tabToggler" can-click="showOrdersByProduct" data-value="pending" data-shows="ordersByProductTable" data-affects="orderToggleTables">Bestellt</a></li>
				@if ($myself->isAdmin)
				<li role="presentation"><a href="#" id="showAllOrdersControl" class="tabToggler" can-click="showOrders" data-shows="ordersTable" data-affects="orderToggleTables">Übersicht</a></li>
				@endif
			</ul>
			<br>
	</div>
	</div>
	<div class="orderToggleTables" id="ordersByProductTable">
		<div class="row">
			@if ($myself->isAdmin)
			<div class="col-xs-12">				
				<div class="btn-toolbar">
					<div class="btn-group btn-group-sm">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Anbieter Auswählen
					    <span class="caret"></span>
					    <span class="sr-only">Toggle Dropdown</span>
						</button>
					  <ul class="dropdown-menu" role="menu">
					  	<li><a href="#" data-value="all" data-property="merchant_id" data-list="ordersByProduct" can-click="filterOrders"><span class="merchant-all"></span>Alle Händler</a></li>
					  	@{{#each merchants}}
					    <li><a href="#" data-value="@{{id}}" data-property="merchant_id" data-list="ordersByProduct" can-click="filterOrders"><span class="merchant-@{{id}}"></span>@{{name}}</a></li>
					    @{{/each}}
					  </ul>
					</div>					
					<div class="btn-group btn-group-sm">
						<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Markierte
					    <span class="caret"></span>
					    <span class="sr-only">Toggle Dropdown</span>
						</button>
					  <ul class="dropdown-menu" role="menu">
					  	@{{#each orderStateSettersActive}}
					    <li>
					    	<a href="#" data-order_state_id="@{{id}}" data-findin="byProductTable" can-click="massToggle">
					    		<span class="orderState-@{{id}}"></span>
					    		@{{name}}
					    	</a>
					    </li>
					    @{{/each}}
					  </ul>
					</div>
					@{{#if ordersByProductShowResume.show}}
					<button type="button" class="btn btn-success btn-sm" can-click="printLastOrder">Letztes Bestellformular&nbsp;<span class="glyphicon glyphicon-save"></span></button>
					<button type="button" class="btn btn-success btn-sm" can-click="orderNow">Bestellung jetzt Auslösen&nbsp;<span class="glyphicon glyphicon-share-alt"></span></button>
				    @{{/if}}
				    @{{#if ordersByProductShowCommissionerButton.show}}
				    <button type="button" class="btn btn-success btn-sm" can-click="printCommissionerTable">Liste für Auspackdienst&nbsp;<span class="glyphicon glyphicon-save"></span></button>
				    @{{/if}}
				</div>
			</div>
			@endif
		</div>		
		<div class="row">
			<div class="col-xs-12">
				<table class="table table-striped table-hover" id="byProductTable">
					<tbody>
						<tr>@if ($myself->isAdmin)
							<th></th>
							@endif
							<th>Produkt<br /><small>Brutto EK pro Gebinde<br />Biokistenpreis pro Einheit</small></th>
							<th>Menge<br /><small>Gebinde</small><br /><small>Bestellungen</small></th>
							<th>Bestellt zwischen / am</th>
							@if ($myself->isAdmin)
							<th>Einheiten Bestellen</th>
							<th>Aktion</th>
							@endif
						</tr>
						@{{#each ordersByProduct}}
						<tr class="@{{colormark totalAmount}} orderState-@{{order_state_id}}">
							@if ($myself->isAdmin)
							<td><input type="checkbox" value="@{{id}}"></td>
							@endif
							<td>
								<strong>@{{sku}}</strong> - @{{name}}<br>
								<strong>@{{merchant.name}}</strong><br>
								<strong>@{{totalForBulk}}€</strong> <small>inkl. @{{taxrate}}% MwSt.</small><br>
								<strong>@{{singleRetailPrice}}€</strong> <small>inkl. MwSt. und Foodkoop Aufschlag</small>
							</td>
							<td>
								<span class="label label-@{{labelClassByPercentage}}">@{{totalAmount}} von @{{units}} @{{unit_unit}}</span>
								<br><span class="label label-default">@{{countOrders}} Bestellung(en)</span></small>
							</td>
							<td>@{{oneOrTwoDates data=earliestOrder field1="earliestOrder" field2="latestOrder"}}</td>
							@if ($myself->isAdmin)
							<td>
								<strong>@{{bulkToOrder}}</strong><br>
							</td>
							<td>
								<button class="btn btn-success btn-sm setOrderState-4"   can-click="toggle" data-order_state_id="4"><span class="glyphicon glyphicon-send" title="Auf die Bestelliste"></span></button>
								<button class="btn btn-warning btn-sm setOrderState-3"   can-click="toggle" data-order_state_id="3"><span class="glyphicon glyphicon-remove-sign" title="Zurück in Wartezustand"></span></button>
								<button class="btn btn-success btn-sm setOrderState-100" can-click="toggle" data-order_state_id="100"><span class="glyphicon glyphicon-ok-sign" title="Vollständig angekommen"></span></button>
								<button class="btn btn-danger btn-sm deleteOrder" can-click="deleteBulk"><span class="glyphicon glyphicon-trash" title="Bestellung löschen"></span></button>
							</td>
							@endif
					    </tr>
					    @{{/each}}
				    </tbody>
				</table>
			</div>
		</div>		
	</div>
	@if ($myself->isAdmin)
	<div class="hidden orderToggleTables" id="ordersTable">
		<div class="row">
			<div class="col-xs-12">
				<table class="table table-striped table-hover">
					<tbody>
						<tr>
							@if ($myself->isAdmin)<th></th>@endif
							<th>Status / Datum</th>
							<th>SKU / Bezeichnung / Anbieter</th>
							<th>Anzahl</th>
							<th>Nettopreis</th>
							<th>Mitglied</th>
							<th colspan="2">Aktion</th>
						</tr>
						@{{#each orders}}
						<tr class="status-@{{order_state_id}}">
							@if ($myself->isAdmin)<td><input type="checkbox" can-value="complete"></td>@endif
							<td><span class="glyphicon status-@{{order_state_id}}"></span> @{{dmY data=created_at field="created_at"}}</td>
							<td>@{{product.sku}}<br>@{{product.name}}<br><strong>@{{merchant.name}}</strong></td>
							<td>@{{amount}} <small>(@{{product.units}})</small></td>
							<td>@{{product.price}}</td>
					        <td>@{{member.name}}</td>
							<td>
								@if ($myself->isAdmin)
								<button class="btn btn-xs btn-default" data-order_state_id='2' can-click="toggle" title="Bestellung in Wartezustand setzen."><span class="glyphicon status-2"></span></button>
								<button class="btn btn-xs btn-warning" data-order_state_id='3' can-click="toggle" title="Bestellung zurückstellen"><span class="glyphicon status-3"></span></button>
								<button class="btn btn-xs btn-success" data-order_state_id='4' can-click="toggle" title="Bestellung in Bestelliste"><span class="glyphicon status-4"></span></button>
								@endif
							</td>
							@if ($myself->isAdmin)
						    <td><button class="btn btn-xs btn-danger" can-click="delete"><span class="glyphicon glyphicon-remove"></span></button></td>
							@endif
					    </tr>
					    @{{/each}}
				    </tbody>
				</table>
			</div>
		</div>
	</div>	
	@endif
</div>
@stop

@section("tabCreateOrder")
<div role="tabpanel" class="tab-pane" id="create">
	<form class="form-horizontal" can-submit="submitOrder" can-reset="resetOrder">
		<div class="row">
			<div class="col-ms-6">
				<h3>Neue Bestellung</h3>
			</div>
			<div class="col-ms-6">

			</div>							
		</div>
		<div id="productInformation" class="2nd-step hidden jumbotron bg-info well">
			<div id="productFormFields" class="">
			</div>
		</div>
		<div class="form-group">
		    <label for="newOrderFormMerchantId" class="col-sm-2 control-label">Anbieter</label>
		    <div class="col-sm-4">
				<select class="form-control" id="newOrderFormMerchantId" name="newOrderFormMerchantId">
					@{{#each merchants}}
					<option value="@{{id}}">@{{name}}</option>
			  		@{{/each}}
				</select>
		    </div>					
		    <label for="newOrderFormProductNumber" class="col-sm-2 control-label">Artikelnummer / -name</label>
		    <div class="col-sm-4">
		      <input type="text" class="form-control" id="newOrderFormProductNumber" name="newOrderFormProductNumber" placeholder="z.B. 112233" can-keyup="findProduct">
		      <div class="autocompleteListWrapper" id="newOrderFormProductListWrapper" style="display:none;">
		      	<ul class="autocompleteListWrapper">
		      	@{{#each newOrderFormProductList}}
		      		<li class="autocompleteListElement" data-sku="@{{sku}}" can-click="newOrderFormAutocompleteClick">@{{name}}</li>
		      	@{{/each}}
		      	</ul>
		      </div>
		    </div>
		</div>
		<div id="newOrderFormDetails" class="2nd-step hidden">
			<div class="form-group">
				<label for="newOrderFormOrderAmount" class="col-sm-2 control-label">Menge</label>
				<div class="col-sm-2">
					<input type="number" min="1" class="form-control" id="newOrderFormOrderAmount" name="newOrderFormOrderAmount" placeholder="z.B. 6" can-change="calculateOrderPrice">
				</div>
				<label for="newOrderFormOrderAmount" class="col-sm-offset-2 col-sm-2 control-label">Gesamtpreis<br><small>ohne/mit MwST</small></label>
				<div class="col-sm-2">
					<input type="text" class="form-control" id="newOrderFormOrderPriceTotalNet" name="newOrderFormOrderPriceTotalNet" disabled>
				</div>																	
				<div class="col-sm-2">
					<input type="text" class="form-control" id="newOrderFormOrderPriceTotalBrt" name="newOrderFormOrderPriceTotalBrt" disabled>
				</div>								
			</div>
			<div class="form-group">
				<label for="newOrderFormOrderComment" class="col-sm-2 control-label">Anmerkungen:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="newOrderFormOrderComment" name="newOrderFormOrderComment">
				</div>
				<div class="col-sm-2">					
					<button class="btn btn-success" type="submit" id="newOrderFormButtonSubmit">Bestellen</button>
				</div>
				<div class="col-sm-2">					
					<button class="btn btn-danger" type="reset" id="newOrderFormButtonCancel">Abbrechen</button>
				</div>								
			</div>
		</div>
	</form>
</div>
@stop

@section("content")
			<orders-app>
				@yield("appHeader")
				<ul class="nav nav-tabs" id="tabNav">
				  <li role="presentation" class="active"><a href="#index" aria-controls="index" id="tabIndexControl">Bestellungen</a></li>
				  <li role="presentation"><a href="#create" aria-controls="create">Neue Bestellung</a></li>
				</ul>
				<div class="tab-content">
					@yield("tabIndex")
					@yield("tabCreateOrder")
				</div>
			</orders-app>
@stop