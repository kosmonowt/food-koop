@extends('layouts.biokiste')
@include('includes.mustache')
@include("includes.bottomJs")
@include("includes.header")

@section("tabIndex")
<div role="tabpanel" class="tab-pane active" id="index">
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
						<td>@{{created_at}}</td>
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
									<div class='setStatus status-3' data-order_state_id='3' can-click="toggle">Bestellt</div>
									<div class='setStatus status-4' data-order_state_id='4' can-click="toggle">Angekommen</div>
									</div>
							</div>
						</td>
					    <td><button can-click="delete">Löschen</button></td>
				    </tr>
				    @{{/each}}
			    </tbody>
			</table>
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
				<h3>Neues Produkt Hinzufügen</h3>
			</div>
			<div class="col-ms-6">

			</div>							
		</div>
		<div class="form-group">
		    <label for="createProductForm_merchant_id" class="col-sm-6 control-label">Anbieter</label>
		    <div class="col-sm-6">
				<select class="form-control" id="createProductForm_merchant_id" name="merchant_id" required >
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
		      <input type="text" class="form-control" id="createProductForm_sku" name="sku" placeholder="z.B. 112233" required >
		    </div>
		</div>
	    <div class="form-group">
		    <label for="createProductForm_name" class="col-sm-6 control-label">Bezeichnung</label>
		    <div class="col-sm-6">
		      <input type="text" class="form-control" id="createProductForm_name" name="name" placeholder="z.B. Brot für die Welt, groß" required >
		    </div>
		</div>
	    <div class="form-group">
		    <label for="createProductForm_price" class="col-sm-6 control-label">Einzelpreis (netto), wie im Katalog</label>
		    <div class="col-sm-6">
		    	<div class="input-group">
		      		<input type="number" class="form-control" id="createProductForm_price" name="price" placeholder="z.B. 2,99" min="0.01" step="0.01" required >
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
		      		<input type="number" class="form-control" id="createProductForm_units" name="units" placeholder="z.B. 6 (Packungen Nudeln)" min="1" required >
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
		      		<input type="number" class="form-control" id="createProductForm_weight_per_unit" name="weight_per_unit" placeholder="z.B. 500" min="1">
		      		<span class="input-group-addon newProductFormTareUnitAddon">g</span>
		    	</div>
		    </div>
		</div>
		<div class="form-group">
			<label for="createProductForm_comment" class="col-sm-6 control-label">Anmerkungen zu diesem Produkt</label>
			<div class="col-sm-6">
				<input type="text" class="form-control" id="createProductForm_comment" name="comment" placeholder="optional">
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-6">
				<button class="btn btn-primary pull-right" type="submit" name="create">Produkt Erstellen</button>
			</div>
			<div class="col-sm-3">
				<div class="input-group">
		      		<input type="number" class="form-control" id="createProductForm_order_amount" name="order_amount" placeholder="wieviel?" min="1">
		      		<span class="input-group-addon newProductFormUnitUnitAddon">Stk</span>										
				</div>
			</div>
			<div class="col-sm-3">									
				<button class="btn btn-success pull-right" type="submit" name="createAndOrder">Erstellen &amp; Bestellen</button>
			</div>

	</form>
</div>
@stop

@section("content")
		<div class="container" id="flashContainer">
		</div>		
		<div class="container">
			<orders-app>
				@yield("appHeader")
				<ul class="nav nav-tabs" id="tabNav">
				  <li role="presentation" class="active"><a href="#index" aria-controls="index" id="tabIndexControl">Übersicht</a></li>
				  <li role="presentation"><a href="#create" aria-controls="create">Neue Bestellung</a></li>
				  <li role="presentation"><a href="#productCreate" aria-controls="productCreate">Produkt hinzufügen</a></li>
				</ul>
				<div class="tab-content">
					@yield("tabIndex")
					@yield("tabCreateOrder")
					@yield("tabCreateProduct")				
				</div>
			</orders-app>
@stop