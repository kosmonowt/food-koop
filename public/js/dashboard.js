can.Component.extend({
  tag: 'dashboard-app',
  scope: {
  	myOrders : new MyOrder.List({}),
  	deleteMyOrder : function(scope,el,ev) {
  		var marketplace = this.marketplace;
  		var id = scope.product_id;
  		var amount = scope.amount;
  		if (confirm("Möchtest Du Diese Bestellung wirklich löschen?")) 
  			scope.destroy().then(function(d,x){
  				handleRestDestroy("Erfolg","Deine Bestellung wurde entfernt.");
  				marketplace.filter(function(mp,i,x){
  					if (mp.attr("id") == id) {
  						mp.attr("remainingAmount",parseInt(mp.attr("remainingAmount")) + amount);
  						mp.attr("availableAmount",parseInt(mp.attr("availableAmount")) + amount);
  					}
  				});
  			},handleRestError);
  	},
  	marketplace : new Marketplace.List({}),
  	marketplaceOrder : function(scope,el,ev) {
  		ev.preventDefault();
  		var amount = el.find("input[name='amount']").val();
  		var product_id = el.find("input[name='product_id']").val();
  		var orderData = {
  			product_id : product_id,
  			merchant_id : el.find("input[name='merchant_id']").val(),
  			amount: amount,
  			comment: "Marktplatz"
  		};
  		var currentMarketplaceOrder = scope;
  		var myOrders = this.myOrders;
  		var marketplace = this.marketplace;
  		new Order(orderData).save().then(function(d,x){
  			currentMarketplaceOrder.attr("remainingAmount",parseInt(currentMarketplaceOrder.attr("remainingAmount")) - amount);
  			currentMarketplaceOrder.attr("availableAmount",parseInt(currentMarketplaceOrder.attr("availableAmount")) - amount);

  			handleRestCreate("Bestellt","Deine Bestellung ist eingegangen.")
  			myOrders.push(d);
  			// if (parseInt(currentMarketplaceOrder.attr("remainingAmount") == 0)) {
  			// 	var nmp = marketplace.filter(function(m,i,x){ return mp.attr("id") != product_id});
  			// 	marketplace.replace(nmp);
  			// }
  		},handleRestError);
  	}
  }
});

var template = can.view("appMustache");
$("body").html(template);
