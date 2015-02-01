AppComponent.extend({
  tag: 'orders-app',
  scope: {
    orders: new Order.List({}),
    orderStates: new OrderState.List({}),
    merchants: new Merchant.List({}),
    productSearch: null,
    productSearchTerm: "",
    toggle: function(b,el,ev) { // Bestellung, Element, Event
      var oldStatus = b.order_state_id;
      b.attr("order_state_id", el.data("order_state_id"));
      b.save();
    },
    findProduct: function(p,el,ev) {
      var acLength = appConfig.product.search.term.autocompleteLength;
      var merchantId = $('#newOrderFormMerchantId').val();
      var val = el.val();
      if (val.length < acLength) return;

      if ( (this.productSearchTerm == "") || (this.productSearchTerm.substr(0,acLength) != val.substr(0,acLength) ) ) {
        this.productSearchTerm = val;
        this.productSearch = new ProductSearch.List({merchantId:merchantId,term:val});
      }
    },
    select: function(orders){
      this.attr('selectedOrder', orders);
    },
    save: function(order) {
      order.save();
      this.removeAttr('selectedOrder');
    }
  }
});

var template = can.view("appMustache");
$("body").html(template);