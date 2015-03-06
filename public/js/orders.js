var productInformationTemplate = can.mustache('{{#product}}'+
  '<input type="hidden" class="productName" name="product_id" value="{{id}}" data-productid="{{id}}">'+
'<div class="form-group form-group-sm">'+
' <label class="col-sm-2">Name:</label>'+
' <div class="col-sm-4">'+
'   <div class="input-group">'+
'     <input type="text" class="form-control" name="product.name" value="{{name}}" disabled>'+
'     <span class="input-group-addon">√</span>'+
'   </div>'+
' </div>'+
' <label class="col-sm-2">Nettopreis:</label>'+
' <div class="col-sm-4">'+
'   <div class="input-group">'+
'     <input type="number" class="form-control" name="product.price" value="{{price}}" min="0.01" range="0.01" disabled>'+
'     <span class="input-group-addon productTaxrate" data-taxrate="{{taxrate}}">€ + {{taxrate}}% </span>'+
'     <span class="input-group-addon">√</span>'+
'   </div>    '+
' </div>'+
'</div>'+
'<div class="form-group form-group-sm">'+
' <label class="col-sm-2">Gebinde:</label>'+
' <div class="col-sm-2"><div class="input-group">'+
'  <input type="number" class="form-control" name="product.units" value="{{units}}" disabled>'+
'     <span class="input-group-addon">√</span>'+
' </div></div>'+
'<div class="col-sm-2">'+
'  <select class="form-control" name="product.unit_unit" disabled><option value="{{unit_unit}}">{{unit_unit}}</select>'+
' </div>'+
' <label class="col-sm-2">Gewicht:</label>'+
' <div class="col-sm-2"><div class="input-group"><input type="number" class="form-control" name="product.weight_per_unit" value="{{weight_per_unit}}">'+
'     <span class="input-group-addon">√</span>'+
'</div></div>'+
'<div class="col-sm-2">'+
'  <select class="form-control" name="product.tare_unit" disabled><option value="{{tare_unit}}">{{tare_unit}}</select>'+
'</div>'+
'</div>'+
'<div class="form-group form-group-sm">'+
' <label class="col-sm-2">Produktart:</label>'+
'<div class="col-sm-4">'+
'  <select class="form-control" name="product.product_type_id" disabled><option value="{{product_type_id}}">{{product_type.name}}</select>'+
'</div>'+
' <label class="col-sm-2">Anmerkungen:</label>'+
' <div class="col-sm-4"><div class="input-group">'+
'  <input type="text" class="form-control" name="product.comment" value="{{comment}}" disabled>'+
'     <span class="input-group-addon">√</span>'+
' </div></div>'+
'</div>'+
  '{{/product}}');


can.Component.extend({
  tag: 'orders-app',
  init: function(){
  },
  scope: {

    orders: new Order.List({}),
    orderStates: new OrderState.List({}),
    merchants: new Merchant.List({}),
    productTypes: new ProductType.List({}),
    newOrderFormProductList: new can.List(),
    productSearchTerm: "",
    product: null,
    toggle: function(b,el,ev) { // Bestellung, Element, Event
      var oldStatus = b.order_state_id;
      b.attr("order_state_id", el.data("order_state_id"));
      b.save();
    },
    findProduct: function(p,el,ev) {
      var acLength = appConfig.product.search.term.autocompleteLength;
      var merchantId = $('#newOrderFormMerchantId').val();
      var val = el.val();
      var list = this.newOrderFormProductList;
      var product = this.product;
      if (val.length < acLength) {
        $("#newOrderFormProductListWrapper").hide();
        return;
      }

      if ( (this.productSearchTerm == "") || (this.productSearchTerm.substr(0,acLength) != val.substr(0,acLength) ) ) {
        
        /** @todo Filter für mehr als drei buchstaben. **/

        this.productSearchTerm = val;
        
        ProductSearch.findAll({merchantId:merchantId,term:val}).then(function(newList,xhr){ 
          newList = JSON.parse(newList);
          if (!newList.length ) {
            $("#newOrderFormProductListWrapper").hide();
          } else {
            $("#newOrderFormProductListWrapper").show();
            list.replace(newList);
            $(".autocompleteListElement").click(function(ev){
              var sku = $(this).data("sku");
              $("#newOrderFormProductListWrapper").hide();
              $("#newOrderFormProductNumber").val(sku);
              $(".2nd-step").removeClass('hidden');
              e = list.filter(function(item,index,list){
                return item.sku == sku;
              });
              $("#productFormFields").html(productInformationTemplate({product:e}));
              $("#newOrderFormDetails input").val("");
            });
          }
        });
      } else {
        $("#newOrderFormProductListWrapper").show();
      }
    },
    select: function(orders){
      this.attr('selectedOrder', orders);
    },
    save: function(order) {
      order.save(function(order){},handleRestError);
      this.removeAttr('selectedOrder');
    },
    delete: function(order) {
      if (confirm("Willst Du diese Bestellung wirklich löschen?")) order.destroy().then(function(){
          handleRestDestroy("Gelöscht:","Die Bestellung wurde gelöscht.")},
          handleRestError);
    },    
    submitOrder: function(scope, el, ev) {
      ev.preventDefault();
      var orderData = {};
      orderData.product_id = $("#newOrderFormProductId").data("productid");
      orderData.merchant_id = $("#newOrderFormMerchantId").val();
      orderData.amount = $("#newOrderFormOrderAmount").val();
      orderData.comment = $("#newOrderFormOrderComment").val();
      var order = new Order(orderData);
      order.save(
        function(order){ 
          scope.orders.unshift(order);
          $(".2nd-step").addClass('hidden');
          handleRestCreate("Bestellung:","Die Bestellung wurde erfolgreich erstellt"); // Success
          gotoIndex();
        },  // Success
        handleRestError // Error
        );
    },
    resetOrder: function(scope, el, ev) {
      /** RESET ORDER FORM **/
      $(".2nd-step").addClass('hidden');
    },
    createProduct: function(scope, el, ev) {
      ev.preventDefault();
      var data = {};
      el.find("input, select, button").each(function(i,x){
        eval("data."+$(this).attr("name")+" = '"+$(this).val()+"';"); // Save Data
      });
      var product = new Product(data);
      product.save(
        function(product){ scope.newOrderFormProductList.push(product);},  // Success
        handleRestError // Error
        );
    },
    calculateOrderPrice: function(scope, el, ev) {
      /** CALC THE PRICE **/
      var amount = parseFloat($("#newOrderFormOrderAmount").val());
      if (!isNaN(amount)) {
        var taxrate = 1 + (parseFloat($("#newOrderFormProductTaxrate").data("taxrate")) / 100);
        var price = parseFloat($("#newOrderFormProductPrice").data("price"));
        var netTotal = Number((price * amount).toFixed(2));
        var total = Number((price * taxrate * amount).toFixed(2));
        $('#newOrderFormOrderPriceTotalNet').val(netTotal+" €");
        $('#newOrderFormOrderPriceTotalBrt').val(total+" €");
      } else {
        $('#newOrderFormOrderPriceTotalNet').val("-/-");
        $('#newOrderFormOrderPriceTotalBrt').val("-/-");        
      }
    }
  }
});

var template = can.view("appMustache");
$("body").html(template);