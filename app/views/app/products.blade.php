@extends('layouts.biokiste')
@include('includes.mustache')
@include("includes.bottomJs")
@include("includes.header")

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
<div role="tabpanel" class="tab-pane active" id="catalogue">
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
					<grid deferredData='productsList'>
					@{{#each items}}
					<tr class="status-@{{order_state_id}}" id="productRow-@{{id}}" data-id="@{{id}}">
						<td><input type="checkbox" value="@{{id}}"></td>
						<td>
							<strong>@{{sku}}</strong>
						</td>
						<td>
							 @{{name}}<br>
							 <strong>@{{merchantName}}</strong>
						</td>
						<td>
							<strong>@{{price}}€ +@{{taxrate}}%</strong> MwSt. 
						</td>
						<td>
							<small>@{{productTypeName}}</small>
						</td>
						<td>
							@{{units}} @{{unit_unit}}<br>
							@{{weight_per_unit}} @{{tare_unit}}
						</td>
						<td>@{{countOrders}}</td>
						@if ($myself->isAdmin)
						<td class="col-xs-2">
							<div class="input-group input-group-sm">
								<input class="form-control" type="number" min="1" range="1" name="amount">
								<span class="input-group-addon bg-succes"><a class="glyphicon glyphicon-send" can-click="quickOrder.order"></a></span>
							</div>
						</td>
						@endif
						<td>
							@{{#if standardProduct}}
							<button class="btn btn-success btn-sm" @if ($myself->isAdmin)can-click="toggleProductState" data-state="1"@endif><span class="glyphicon glyphicon-star" title="Grundbedarf"></span></button>
							@{{else}}
							<button class="btn btn-default btn-sm" @if ($myself->isAdmin)can-click="toggleProductState" data-state="3"@endif><span class="glyphicon glyphicon-star-empty" title="Zum Grundbedarf"></span></button>
							@{{/if}}
						</td>
					    <td>
							<button class="btn btn-info btn-sm" can-click="editProduct" data-id="@{{id}}"><span class="glyphicon glyphicon-edit"></span></button>
							@if ($myself->isAdmin)
					    	@{{#if blocked}}
					    	<button class="btn btn-default btn-sm" can-click="toggleProductState" data-state="1"><span class="glyphicon glyphicon-lock" title="Entsperren"></span></button>
					    	@{{else}}
					    	<button class="btn btn-warning btn-sm" can-click="toggleProductState" data-state="2"><span class="glyphicon glyphicon-ban-circle" title="Sperren"></span></button>
					    	@{{/if}}
					    	<button class="btn btn-danger btn-sm" can-click="deleteProduct"><span class="glyphicon glyphicon-trash"></span></button>
					    	@endif
				    	</td>
				    </tr>
				    @{{/each}}
					</grid>
			    </tbody>
			</table>
		    <next-prev paginate='paginate'></next-prev>
		    <page-count page='paginate.page' count='paginate.pageCount'></page-count>			
		</div>
	</div>
</div>
@stop

@section("dynamicScripts")
<script type="text/javascript">
	productGridHead = "<thead><tr>"+
		"<th></th>"+
		"<th>SKU</th>"+
		"<th>Produkt</th>"+
		"<th>Preis</th>"+
		"<th>Menge</th>"+
		"<th>Typ</th>"+
		"<th>Orders</th>"+
		@if ($myself->isAdmin)
		"<th>Gebinde bestellen</th>"+
		@endif						
		"<th title=\"Grundbedarf\">GB</th>"+
		"<th>Aktion</th>"+
	"</tr></thead>";
</script>
@stop

@section("content")
			<products-app>
				@yield("appHeader")
				<ul class="nav nav-tabs" id="tabNav">
				  <li role="presentation" class="active" id="controlProductIndex"><a href="#catalogue" aria-controls="catalogue" id="">Katalog</a></li>
				  <li role="presentation" id="controlProductCreate"><a href="#productCreate" aria-controls="productCreate" can-click="resetCreateProductForm">Produkt hinzufügen</a></li>
				</ul>
				<div class="tab-content">
					@yield("tabCreateProduct")
					@yield("tabCatalogue")
				</div>
			</products-app>
@stop