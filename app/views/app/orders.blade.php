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
				<li role="presentation"><a href="#" id="showAllOrdersControl" class="tabToggler" can-click="showOrders" data-shows="ordersTable" data-affects="orderToggleTables">Übersicht</a></li>
			</ul>
			<br>
	</div>
	</div>
	<div class="orderToggleTables" id="ordersByProductTable">
		<div class="row">
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
					<div class="">
						<button type="button" class="btn btn-success btn-sm" can-click="orderNow">Bestellung jetzt Auslösen&nbsp;<span class="glyphicon glyphicon-share-alt"></span></button>
					</div>
				    @{{/if}}
				</div>
			</div>
		</div>		
		<div class="row">
			<div class="col-xs-12">
				<table class="table table-striped table-hover" id="byProductTable">
					<tbody>
						<tr>
							<th></th>
							<th>Produkt</th>
							<th>Menge</th>
							<th>Bestellt zwischen / am</th>
							<th>Einheiten Bestellen</th>
							<th>Aktion</th>
						</tr>
						@{{#each ordersByProduct}}
						<tr class="@{{colormark totalAmount}} orderState-@{{order_state_id}}">
							<td onclick="$(this).children('input').click();"><input type="checkbox" value="@{{id}}"></td>
							<td>
								<strong>@{{sku}}</strong> - @{{name}}<br>
								<strong>@{{totalForBulk}}€</strong> inkl. @{{taxrate}}% MwSt. (Einzelpreis: @{{price}}€)<br>
								<strong>@{{merchant.name}}</strong><br>
							</td>
							<td>
								<strong>@{{totalAmount}}</strong> von (@{{units}} @{{unit_unit}})
								<br>in @{{countOrders}} Bestellungen
							</td>
							<td>@{{oneOrTwoDates data=earliestOrder field1="earliestOrder" field2="latestOrder"}}</td>
							<td>
								<strong>@{{bulkToOrder}}</strong><br>
							<td>
								<button class="btn btn-success btn-sm setOrderState-3"   can-click="toggle" data-order_state_id="3"><span class="glyphicon glyphicon-send" title="Auf die Bestelliste"></span></button>
								<button class="btn btn-warning btn-sm setOrderState-2"   can-click="toggle" data-order_state_id="2"><span class="glyphicon glyphicon-remove-sign" title="Zurück in Wartezustand"></span></button>
								<button class="btn btn-success btn-sm setOrderState-100" can-click="toggle" data-order_state_id="100"><span class="glyphicon glyphicon-ok-sign" title="Vollständig angekommen"></span></button>
							</td>
					    </tr>
					    @{{/each}}
				    </tbody>
				</table>
			</div>
		</div>		
	</div>
	<div class="hidden orderToggleTables" id="ordersTable">
		<div class="row">
			<div class="col-xs-12">
				<table class="table table-striped table-hover">
					<tbody>
						<tr>
							<th></th>
							<th>Datum</th>
							<th>Nummer</th>
							<th>Bezeichnung</th>
							<th>Anbieter</th>
							<th>Anzahl</th>
							<th>Nettopreis</th>
							<th>Mitglied</th>
							<th colspan="2">Aktion</th>
						</tr>
						@{{#each orders}}
						<tr class="status-@{{order_state_id}}">
							<td><input type="checkbox" can-value="complete"></td>
							<td>@{{dmY data=created_at field="created_at"}}</td>
							<td>@{{product.sku}}</td>
							<td>@{{product.name}}</td>
							<td>@{{merchant.name}}</td>
							<td>@{{amount}} <small>(@{{product.units}})</small></td>
							<td>@{{product.price}}</td>
					        <td>@{{member.name}}</td>
							<td>
								<div class="statusIcon status-@{{order_state_id}}" onclick="$(this).children('.statusSetter').toggle();">
									<div class='statusSetter' style="display:none;">
										<div class='setStatus status-1' data-order_state_id='1' can-click="toggle">Wartend</div>
										<div class='setStatus status-2' data-order_state_id='2' can-click="toggle">Zurückgestellt</div>
										<div class='setStatus status-3' data-order_state_id='3' can-click="toggle">Auf Bestelliste</div>
										<div class='setStatus status-4' data-order_state_id='4' can-click="toggle">Bestellt</div>
										</div>
								</div>
							</td>
						    <td><button class="btn btn-danger" can-click="delete"><span class="glyphicon glyphicon-remove"></span></button></td>
					    </tr>
					    @{{/each}}
				    </tbody>
				</table>
			</div>
		</div>
	</div>	
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
		      		<li class="autocompleteListElement" data-sku="@{{sku}}">@{{name}}</li>
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

@section("tabCreateProduct")
<div role="tabpanel" class="tab-pane" id="productCreate">
	<form class="form-horizontal" can-submit="createProduct" can-reset="resetOrder">
		<div class="row">
			<div class="col-ms-6">
				<h3>@{{currentProductForm.caption}}</h3>
			</div>
			<div class="col-ms-6">

			</div>							
		</div>
		<div class="form-group">
			<input type="hidden" name="id" value="@{{newProduct.id}}">
		    <label for="createProductForm_merchant_id" class="col-sm-6 control-label">Anbieter</label>
		    <div class="col-sm-6">
				<select class="form-control" id="createProductForm_merchant_id" name="merchant_id" required>
					<option value="">Bitte Wählen</option>
					@{{#each merchants}}
					<option value="@{{id}}">@{{name}}</option>
			  		@{{/each}}
				</select>
		    </div>	
	    </div>
		<div class="form-group">
		    <label for="createProductForm_productTypeId" class="col-sm-6 control-label">Produktart</label>
		    <div class="col-sm-6">
				<select class="form-control" id="createProductForm_product_type_id" name="product_type_id" onchange="$('#priceInclTax').html('+'+$(this).children(':selected').data('tax')+'% MwSt.'); $('#priceInclTax').data('factor',(1 + (parseInt($(this).children(':selected').data('tax'))/100)));" required >
					<option value="">Bitte Wählen</option>
					@{{#each productTypes}}
					<option value="@{{id}}" data-tax="@{{tax}}">@{{name}}</option>
			  		@{{/each}}
				</select>
		    </div>	
	    </div>						    
	    <div class="form-group">
		    <label for="createProductForm_sku" class="col-sm-6 control-label">Artikelnummer / SKU</label>
		    <div class="col-sm-6">
		      <input type="text" class="form-control" id="createProductForm_sku" name="sku" placeholder="z.B. 112233" value="@{{newProduct.sku}}" required >
		    </div>
		</div>
	    <div class="form-group">
		    <label for="createProductForm_name" class="col-sm-6 control-label">Bezeichnung</label>
		    <div class="col-sm-6">
		      <input type="text" class="form-control" id="createProductForm_name" name="name" placeholder="z.B. Brot für die Welt, groß" value="@{{newProduct.name}}" required >
		    </div>
		</div>
	    <div class="form-group">
		    <label for="createProductForm_price" class="col-sm-6 control-label">Einzelpreis (netto), wie im Katalog</label>
		    <div class="col-sm-6">
		    	<div class="input-group">
		      		<input type="number" class="form-control" id="createProductForm_price" name="price" value="@{{newProduct.price}}" placeholder="z.B. 2,99" min="0.01" step="0.01" required >
		      		<span class="input-group-addon">€</span>
		      		<span class="input-group-addon" id="priceInclTax" data-factor="0">Produktart auswählen</span>
		      	</div>
		    </div>
		</div>
		<div class="form-group">
			<label for="createProductForm_unit_unit" class="col-sm-6 control-label">Verpackungseinheit</label>
			<div class="col-sm-6">
	      		<select class="form-control" id="createProductForm_unit_unit" name="unit_unit" onchange="$('.newProductFormUnitUnitAddon').html($(this).val());" required >
	      			<option value="Stk" selected>Stk (Stück / Packungen)</option>
	      			<option value="g">g (Gramm)</option>
	      			<option value="kg">kg (Kilogramm)</option>
	      			<option value="l">l (Liter)</option>
	      			<option value="Fl">Fl (Flasche)</option>
	      			<option value="Sck">Sck (Sack)</option>
	      		</select>
	      	</div>
		</div>
	    <div class="form-group">
		    <label for="createProductForm_units" class="col-sm-6 control-label">Verpackungseinheit / Anzahl pro Gebinde</label>
		    <div class="col-sm-6">
		    	<div class="input-group">
		      		<input type="number" class="form-control" id="createProductForm_units" name="units" value="@{{newProduct.units}}" placeholder="z.B. 6 (Packungen Nudeln)" min="1" required >
		      		<span class="input-group-addon newProductFormUnitUnitAddon">Stk</span>
		    	</div>
		    	
		    </div>
		</div>
		<div class="form-group">
			<label for="createProductForm_tare_unit" class="col-sm-6 control-label">Gewichtseinheit</label>
			<div class="col-sm-6">
	      		<select class="form-control" id="createProductForm_tare_unit" name="tare_unit" onchange="$('.newProductFormTareUnitAddon').html($(this).val());">
	      			<option value="mg">mg (Milligramm)</option>
	      			<option value="g" selected>g (Gramm)</option>
	      			<option value="kg">kg (Kilogramm)</option>
	      			<option value="ml">ml (Milliliter)</option>
	      			<option value="l">l (Liter)</option>
	      		</select>
	      	</div>
		</div>
	    <div class="form-group">
		    <label for="createProductForm_weight_per_unit" class="col-sm-6 control-label">Gewicht Pro Stück (falls bekannt)</label>
		    <div class="col-sm-6">
		    	<div class="input-group">
		      		<input type="number" class="form-control" id="createProductForm_weight_per_unit" value="@{{newProduct.weight_per_unit}}" name="weight_per_unit" placeholder="z.B. 500" min="1">
		      		<span class="input-group-addon newProductFormTareUnitAddon">g</span>
		    	</div>
		    </div>
		</div>
		<div class="form-group">
			<label for="createProductForm_comment" class="col-sm-6 control-label">Anmerkungen zu diesem Produkt</label>
			<div class="col-sm-6">
				<input type="text" class="form-control" id="createProductForm_comment" name="comment" value="@{{newProduct.comment}}" placeholder="optional">
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-6">
				<button class="btn btn-primary pull-right" type="submit" name="@{{currentProductForm.buttonName}}">@{{currentProductForm.buttonCaption}}</button>
			</div>
			@{{#if currentProductForm.showOrderAndSave}}
			<div class="col-sm-3">
				<div class="input-group">
		      		<input type="number" class="form-control" id="createProductForm_order_amount" name="order_amount" placeholder="wieviel?" min="1">
		      		<span class="input-group-addon newProductFormUnitUnitAddon">Stk</span>										
				</div>
			</div>
			<div class="col-sm-3">									
				<button class="btn btn-success pull-right" type="submit" name="createAndOrder">Erstellen &amp; Bestellen</button>
			</div>
			@{{/if}}
		</div>
	</form>
</div>
@stop

@section("tabCatalogue")
<div role="tabpanel" class="tab-pane" id="catalogue">
	<br>
	<div class="row">
		<div class="col-sm-6">
			<ul class="nav nav-pills" id="orderFilterButtons">
				<li role="presentation" class="dropdown">
					<a href="#" class="tabToggler dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						Händler
						<span class="caret"></span>
					</a>
				    <ul class="dropdown-menu" role="menu">
				    	<li role="presentation"><a href="#" class="tabToggler" can-click="filterProducts" data-scope="merchant_id" data-filter="all">Alle Händler</a></li>
				    	@{{#each merchants}}
				    	<li role="presentation"><a href="#" can-click="filterProducts" data-scope="merchant_id" data-filter="@{{id}}">@{{name}}</a></li>
				    	@{{/each}}
				    </ul>
				</li>
				<li role="presentation"><a href="#" class="tabToggler" can-click="filterProducts" data-scope="product_state_id" data-filter="all">Alle Produkte</a></li>
				<li role="presentation"><a href="#" class="tabToggler" can-click="filterProducts" data-scope="product_state_id" data-filter="standard">Grundbedarf</a></li>
			</ul>
		</div>
		<div class="col-sm-6">
			<div class="input-group">
				<span class="input-group-addon">SKU / Name</span>
				<input class="form-control" type="text" name="productsTerm" can-keyup="searchProducts" data-scope="search" data-filter="scope">
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-xs-12">
			<table class="table table-striped table-hover">
				<tbody>
					<tr>
						<th></th>
						<th>Produkt</th>
						<th>Menge</th>
						<th>Orders</th>
						<th>Gebinde bestellen</th>
						<th>GB</th>
						<th>Aktion</th>
					</tr>
					@{{#each products}}
					<tr class="status-@{{order_state_id}}" id="productRow-@{{id}}" data-id="@{{id}}">
						<td onclick="$(this).children('input').click();"><input type="checkbox" value="@{{id}}"></td>
						<td>
							<strong>@{{sku}}</strong> - @{{name}}<br>
							<strong>@{{price}}€ +@{{taxrate}}%</strong> MwSt. <small>(@{{productTypeName}})</small><br>
							<strong>@{{merchantName}}</strong><br>
						</td>
						<td>
							@{{units}} @{{unit_unit}}<br>
							@{{weight_per_unit}} @{{tare_unit}}
						</td>
						<td>@{{countOrders}}</td>
						<td class="col-xs-2">
							<div class="input-group input-group-sm">
								<input class="form-control" type="number" min="1" range="1" name="amount">
								<span class="input-group-addon bg-succes"><a class="glyphicon glyphicon-send" can-click="quickOrder"></a></span>
							</div>
						<td>
							@{{#if standardProduct}}
							<button class="btn btn-success btn-sm" can-click="toggleProductState" data-state="1"><span class="glyphicon glyphicon-star" title="Grundbedarf"></span></button>
							@{{else}}
							<button class="btn btn-default btn-sm" can-click="toggleProductState" data-state="3"><span class="glyphicon glyphicon-star-empty" title="Zum Grundbedarf"></span></button>
							@{{/if}}
						</td>
					    <td>
							<button class="btn btn-info btn-sm" can-click="editProduct" data-id="@{{id}}"><span class="glyphicon glyphicon-edit"></span></button>
					    	@{{#if blocked}}
					    	<button class="btn btn-default btn-sm" can-click="toggleProductState" data-state="1"><span class="glyphicon glyphicon-lock" title="Entsperren"></span></button>
					    	@{{else}}
					    	<button class="btn btn-warning btn-sm" can-click="toggleProductState" data-state="2"><span class="glyphicon glyphicon-ban-circle" title="Sperren"></span></button>
					    	@{{/if}}
					    	<button class="btn btn-danger btn-sm" can-click="deleteProduct"><span class="glyphicon glyphicon-trash"></span></button></td>
				    </tr>
				    @{{/each}}
			    </tbody>
			</table>
		</div>
	</div>
</div>
@stop

@section("content")
		<div class="container" id="flashContainer">
		</div>		
		<div class="container">
			<orders-app>
				@yield("appHeader")
				<ul class="nav nav-tabs" id="tabNav">
				  <li role="presentation" class="active"><a href="#index" aria-controls="index" id="tabIndexControl">Bestellungen</a></li>
				  <li role="presentation"><a href="#create" aria-controls="create">Neue Bestellung</a></li>
				  <li role="presentation" id="controlProductCreate"><a href="#productCreate" aria-controls="productCreate" can-click="resetCreateProductForm">Produkt hinzufügen</a></li>
				  <li role="presentation" id="controlProductIndex"><a href="#catalogue" aria-controls="catalogue" id="">Katalog</a></li>
				</ul>
				<div class="tab-content">
					@yield("tabIndex")
					@yield("tabCreateOrder")
					@yield("tabCreateProduct")				
					@yield("tabCatalogue")
				</div>
			</orders-app>
@stop