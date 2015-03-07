/*======================================================================*/
/*==================== MUSTACHE TEMPLATE SECTION =======================*/
/*======================================================================*/

var productInformationTemplate = can.mustache('{{#product}}'+
'<input type="hidden" class="productName" name="product_id" value="{{id}}" data-productid="{{id}}">'+
'<div class="form-group form-group-sm">'+
' <label class="col-sm-2">Name:</label>'+
' <div class="col-sm-4">'+
'   <div class="input-group">'+
'     <input type="text" class="form-control" name="product.name" value="{{name}}" disabled>'+
'     <span class="input-group-addon"><span class="glyphicon glyphicon-edit makeEditableButton" title="Bearbeiten" can-click="enableInput" data-enable="product.name"></span></span>'+
'   </div>'+
' </div>'+
' <label class="col-sm-2">Nettopreis:</label>'+
' <div class="col-sm-4">'+
'   <div class="input-group">'+
'     <input type="number" class="form-control" name="product.price" value="{{price}}" min="0.01" range="0.01" disabled>'+
'     <span class="input-group-addon productTaxrate" data-taxrate="{{taxrate}}">€ + {{taxrate}}% </span>'+
'     <span class="input-group-addon"><span class="glyphicon glyphicon-edit" title="Bearbeiten" can-click="enableInput" data-enable="product.price,product.product_type_id"></span></span>'+
'   </div>    '+
' </div>'+
'</div>'+
'<div class="form-group form-group-sm">'+
' <label class="col-sm-2">Gebinde:</label>'+
' <div class="col-sm-2"><div class="input-group">'+
'  <input type="text" class="form-control" name="product.units" value="{{units}}" disabled>'+
'     <span class="input-group-addon"><span class="glyphicon glyphicon-edit makeEditableButton" title="Bearbeiten" can-click="enableInput" data-enable="product.units,product.unit_unit"></span></span>'+
' </div></div>'+
'<div class="col-sm-2">'+
'  <select class="form-control" name="product.unit_unit" disabled><option value="{{unit_unit}}">{{unit_unit}}</select>'+
' </div>'+
' <label class="col-sm-2">Gewicht:</label>'+
' <div class="col-sm-2"><div class="input-group"><input type="text" class="form-control" name="product.weight_per_unit" value="{{weight_per_unit}}">'+
'     <span class="input-group-addon"><span class="glyphicon glyphicon-edit makeEditableButton" title="Bearbeiten" can-click="enableInput" data-enable="product.weight_per_unit,product.tare_unit"></span></span>'+
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
'     <span class="input-group-addon"><span class="glyphicon glyphicon-edit makeEditableButton" title="Bearbeiten" can-click="enableInput" data-enable="product.comment"></span></span>'+
' </div></div>'+
'</div>'+
  '{{/product}}');

/*======================================================================*/
/*================== COMPONENT CONTROLLER SECTION ======================*/
/*======================================================================*/

can.Component.extend({
  tag: 'orders-app',
  scope: {
    orders: new Order.List({}),
    ordersByProduct: new can.List(),
    ordersByProductUnfiltered: new can.List(),
    ordersByProductScope: null,
    ordersByProductShowResume: new can.Map({"show":false}),
    orderStateSettersActive: new can.List({}),
    ordersList: new can.List({}),
    orderStates: new OrderState.List({}),
    merchants: new Merchant.List({}),
    productTypes: new ProductType.List({}),
    newOrderFormProductList: new can.List(),
    orderResume: new can.Map({}),
    productSearchTerm: "",
    product: null,
    /** Toggle order_state for particluar order **/
    toggle: function(b,el,ev) {
      if (typeof(b.bulkToOrder) == "undefined") {
        // Classical Order
        b.attr("order_state_id", el.data("order_state_id"));
        b.save();
      } else {
        var list = this.ordersByProduct;
        var unfiltered = this.ordersByProductUnfiltered;
        // Order By Product
        can.$.ajax({
          url: sUrl+'orders/bulk/'+b.id+"/"+el.data("order_state_id"),
          type: 'POST',
          dataType: 'json',
          data: {"earliestOrder":b.earliestOrder,"latestOrder":b.latestOrder}
        })
        .done(function() {
          list.replace(list.filter(function(i,x,l){ return (b.id != i.id); }));
          unfiltered.replace(list);
        })
        .fail(handleRestError);        
      }
    },
    orderNow: function(scope,el,ev) {
      var list = this.ordersByProduct;
      can.$.ajax({
        url: sUrl+"orders/bulk/applyOrder",
        type: "GET",
        dataType: "json"
      }).done(function(){
        handleRestCreate("Bestellung","Bestellung erfolgreich abgeschickt.");
        list.replace(new can.List({}));
      }).fail(handleRestError);
    },
    massToggle: function(scope,el,ev) {
      var list = this.ordersByProduct;
      var unfiltered = this.ordersByProductUnfiltered;      
      var table = $("#"+$(el).data("findin")); //Element id is found in data-findin="{id}"
      var product_ids = [];
      table.find("input:checked").each(function(){product_ids.push($(this).val());});
      can.$.ajax({
        url: sUrl+"orders/bulk/"+el.data("order_state_id"),
        type: "POST",
        dataType: "json",
        data: {"product_ids":product_ids}
      }).done(function(){
          list.replace(list.filter(function(i,x,l){
            return (product_ids.indexOf((i.id).toString()) == -1); 
        }));
          unfiltered.replace(list);
      }).fail(handleRestError);
    },
    setSettableOrderStates: function(orderState) {
      var scopeToState = {"waiting":[3],"listed":[2],"pending":[2,100]};
      var activeStates = eval("scopeToState."+orderState);
      this.orderStateSettersActive.replace(this.orderStates.filter(function(i,x,l){return activeStates.indexOf(i.id) >= 0;}));
    },
    /** UI Action when Pill Menu Button "All orders" is clicked **/
    showOrders: function(scope,el,ev) {
      tabToggler($(el).data("shows"),$(el).data("affects"));
    },
    /** UI Action when Pill Menu Button "Orders by Product" is clicked **/
    showOrdersByProduct: function(scope,el,ev) {
      tabToggler($(el).data("shows"),$(el).data("affects"));
      var queryScope = el.data("value");
      this.setSettableOrderStates(queryScope);
      if (!this.ordersByProduct.length || (this.ordersByProductScope != queryScope) ) {
        this.ordersByProductScope = queryScope;
        if (queryScope == "listed") {
          this.ordersByProductShowResume.attr("show",true);
        } else {
          this.ordersByProductShowResume.attr("show",false);
        }
        var list = this.ordersByProduct;
        OrderSearch.findByProduct([queryScope]).then(function(newList,xhr){
          newList = JSON.parse(newList);
          list.replace(newList);
        },handleRestError);
      }
    },
    /** 
      * This one orderes any of the lists here. It filters the orders by a property
      * REQUIRES data-list, data-property and data-value
      * also REQUIRES a backup "unfiltered" list 
      * to not reload from server anytime you modify the filter
      **/ 
    filterOrders: function(scope, el, ev) {
      var listName = $(el).data("list");
      var listProperty  = $(el).data("property"); // the property to filter with
      var filterValue = $(el).data("value");
      var list = eval("this."+listName);
      var unfiltered = eval("this."+listName+"Unfiltered"); // Unfiltered List has to be declared in scope.
      if (filterValue != "all") {
        if (unfiltered.length) {
          // We try to reload the list from the unfiltered backup once we already filtered the list.
          list.replace(unfiltered);
        } else {
          // Otherwise we will backup the original list into that unfiltered backup.
          unfiltered.replace(list);
        }
        var filtered = list.filter(function(item, index, list) {
          var findProperty = eval("item."+listProperty);
          var compareTo = filterValue;
          return (findProperty == compareTo);
        });
        list.replace(filtered);
      } else if (unfiltered.length) {
        // Happens when switching to "all" after previous filtering to another value
        list.replace(unfiltered);
      }
    },
    /** Action called on product search (in add Order Tab) is clicked **/
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
        },handleRestError);
      } else {
        $("#newOrderFormProductListWrapper").show();
      }
    },
    select: function(orders){ this.attr('selectedOrder', orders); },
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
      orderData.product_id = $("input[name='product_id']").val();
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
    },
    enableInput: function(scope,el,ev) {
      console.log(scope);
      console.log(el);
      console.log(ev);      
      if ($(this).hasClass('glyphicon-edit')) {
        $(this).removeClass('glyphicon-edit').addClass("glyphicon-ok");
        enable = el.data("enable").split(",").each(function(i,x){
          $("input[name='"+x+"'], select[name='"+x+"']").removeAttr("disabled");
        });
      } else if($(this).hasClass("glyphicon-ok")) {
        console.log("check");
      }

    }    
  }
});

var template = can.view("appMustache");
$("body").html(template);