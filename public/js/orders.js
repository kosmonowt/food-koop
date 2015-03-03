var productInformationTemplate = can.mustache('{{#product}}'+
  '<div class="row"><label class="col-sm-2">Name:</label><div class="col-sm-4"><span class="productName" id="newOrderFormProductId" data-productid="{{id}}">{{name}}</span></div>'+
  '<label class="col-xs-2">Nettopreis:</label><div class="col-xs-1"><span class="productPrice" id="newOrderFormProductPrice" data-price="{{price}}">{{price}}€</span></div>'+
  '<label class="col-xs-2">MwSt:</label><div class="col-xs-1"><span class="productTaxrate" id="newOrderFormProductTaxrate" data-taxrate="{{taxrate}}">{{taxrate}}%</span></div></div>'+  
  '<div class="row"><label class="col-xs-2">Gebinde:</label><div class="col-xs-4">{{units}} {{unit_unit}}</div>'+
  '<label class="col-xs-2">Gewicht:</label><div class="col-xs-4">{{weight_per_unit}} {{tare_unit}} / VE</div></div>'+
  '{{/product}}');


can.Component.extend({
  tag: 'orders-app',
  init: function(){
  },
  scope: {

    orders: new Order.List({}),
    orderStates: new OrderState.List({}),
    merchants: new Merchant.List({}),
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