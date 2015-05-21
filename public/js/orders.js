/*======================================================================*/
/*==================== MUSTACHE TEMPLATE SECTION =======================*/
/*======================================================================*/

var productInformationTemplate = can.mustache('{{#product}}'+
'<input type="hidden" class="productName" name="product_id" value="{{id}}" data-productid="{{id}}">'+
'<div class="form-group form-group-sm">'+
' <label class="col-sm-2">Name:</label>'+
' <div class="col-sm-4">'+
'   <div class="input-group">'+
'     <input type="text" class="form-control" name="product.name" value="{{name}}" readonly>'+
'     <span class="input-group-addon"><span class="glyphicon glyphicon-edit makeEditableButton" title="Bearbeiten" can-click="enableInput" data-enable="product.name"></span></span>'+
'   </div>'+
' </div>'+
' <label class="col-sm-2">Nettopreis:</label>'+
' <div class="col-sm-4">'+
'   <div class="input-group">'+
'     <input type="number" class="form-control" id="newOrderFormProductPrice" name="product.price" value="{{price}}" min="0.01" range="0.01" readonly>'+
'     <span class="input-group-addon productTaxrate" id="newOrderFormProductTaxrate" data-taxrate="{{taxrate}}">€ + {{taxrate}}% </span>'+
'     <span class="input-group-addon"><span class="glyphicon glyphicon-edit" title="Bearbeiten" can-click="enableInput" data-enable="product.price,product.product_type_id"></span></span>'+
'   </div>'+
' </div>'+
'</div>'+
'<div class="form-group form-group-sm">'+
' <label class="col-sm-2">Gebinde:</label>'+
' <div class="col-sm-2"><div class="input-group">'+
'  <input type="text" class="form-control" name="product.units" value="{{units}}" readonly>'+
'     <span class="input-group-addon"><span class="glyphicon glyphicon-edit makeEditableButton" title="Bearbeiten" can-click="enableInput" data-enable="product.units,product.unit_unit"></span></span>'+
' </div></div>'+
'<div class="col-sm-2">'+
'  <select class="form-control" name="product.unit_unit" readonly><option value="{{unit_unit}}">{{unit_unit}}</select>'+
' </div>'+
' <label class="col-sm-2">Gewicht:</label>'+
' <div class="col-sm-2"><div class="input-group"><input type="text" class="form-control" name="product.weight_per_unit" value="{{weight_per_unit}}" readonly>'+
'     <span class="input-group-addon"><span class="glyphicon glyphicon-edit makeEditableButton" title="Bearbeiten" can-click="enableInput" data-enable="product.weight_per_unit,product.tare_unit"></span></span>'+
'</div></div>'+
'<div class="col-sm-2">'+
'  <select class="form-control" name="product.tare_unit" readonly><option value="{{tare_unit}}">{{tare_unit}}</select>'+
'</div>'+
'</div>'+
'<div class="form-group form-group-sm">'+
' <label class="col-sm-2">Produktart:</label>'+
'<div class="col-sm-4">'+
'  <select class="form-control" name="product.product_type_id" readonly><option value="{{product_type_id}}">{{product_type.name}}</select>'+
'</div>'+
' <label class="col-sm-2">Anmerkungen:</label>'+
' <div class="col-sm-4"><div class="input-group">'+
'  <input type="text" class="form-control" name="product.comment" value="{{comment}}" readonly>'+
'     <span class="input-group-addon"><span class="glyphicon glyphicon-edit makeEditableButton" title="Bearbeiten" can-click="enableInput" data-enable="product.comment"></span></span>'+
' </div></div>'+
'{{#if blocked}}<div class="row"><div class="col-sm-12"><span class="text-danger text-center">Dieses Produkt ist derzeit blockiert. Bitte wende Dich an die Bestellgruppe um mehr zu erfahren.</span></div></div>{{/if}}'+
'</div>'+
  '{{/product}}');

/*======================================================================*/
/*================== COMPONENT CONTROLLER SECTION ======================*/
/*======================================================================*/

can.Component.extend({
  tag: 'orders-app',
  scope: {
    orderStates: new OrderState.List({}), // OrderStates existing
    merchants: new Merchant.List({}), // Merchants existing
    productTypes: new ProductType.List({}), // Product Types existing
    orders: new can.List(), // All Orders View
    ordersByProduct: new can.List(OrderSearch.findByProduct(["waiting"]).then(function(newList,xhr){ return JSON.parse(newList);})), // Orders By Product View
    ordersByProductUnfiltered: new can.List(), // Second list for caching values
    ordersByProductScope: null, // Scope for Orders By Product View
    ordersByProductShowResume: new can.Map({"show":false}), // Indicates if onList Orders are now shown and if to show "Order Now" Button
    orderStateSettersActive: new can.List({}), // In Orders By Product View, list that indicates the actions that could be taken (states to set) with the orders
    newProduct: new can.Map({}), // Product to be edited/created
    newOrderFormProductList: new can.List(),
    createProductForm: new can.Map({"titleCaption":"Neues Produkt Hinzufügen","showOrderAndSave":true,"buttonCaption":"Produkt Erstellen","buttonName":"create"}),
    updateProductForm: new can.Map({"titleCaption":"Produkt Bearbeiten","showOrderAndSave":false,"buttonCaption":"Änderungen Speichern","buttonName":"update"}),
    currentProductForm: new can.Map({}),
    orderResume: new can.Map({}),
    productSearchTerm: "",
    product: null,
    products: new Product.List({}), // All Products existing
    allProducts: new can.List(),  // Cached view for products
    productsScopes: new can.Map({"merchant_id":"all","product_state_id":"all","term":""}),

    /** Is called when New Product is being Clicked **/
    resetCreateProductForm: function(b,el,ev) {
      removeAllAttr(this.newProduct);
      $("#productCreate form").trigger("reset");
      replaceAllAttr(this.currentProductForm,this.createProductForm);
    },
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
      var scopeToState = {"waiting":[4],"listed":[3],"pending":[3,100]};
      var activeStates = eval("scopeToState."+orderState);
      this.orderStateSettersActive.replace(this.orderStates.filter(function(i,x,l){return activeStates.indexOf(i.id) >= 0;}));
    },
    /** UI Action when Pill Menu Button "All orders" is clicked **/
    showOrders: function(scope,el,ev) {
      if (!this.orders.length) {
        var orders = this.orders;
        Order.findAll().then(function(d){orders.replace(d);},handleRestError);
      }
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
    /** Removes a Product from the database **/
    deleteProduct: function(product) {
      var products = this.products;
      if (confirm("Soll dieses Produkt wirklich aus der Datenbank entfernt werden?")) {
        var p = new Product(product);
        var id = p.attr("id");
        p.destroy().then(function(){
          products.replace(products.filter(function(i,x,l){return i.attr("id") != id;}));
          handleRestDestroy("Gelöscht:","Das Produkt wurde aus der Datenbank entfernt.");
        },handleRestError);
      } 
    },
    select: function(orders){ this.attr('selectedOrder', orders); },
    save: function(order) {
      order.save(function(order){},handleRestError);
      this.removeAttr('selectedOrder');
    },
    delete: function(order) {
      if (confirm("Willst Du diese Bestellung wirklich löschen?")) {
        var orders = this.orders;
        var id = order.attr("id");
        order.destroy().then(function(){
          handleRestDestroy("Gelöscht:","Die Bestellung wurde gelöscht.");
          orders.replace(orders.filter(function(i,x,l){return i.attr("id") != id;}));
        },handleRestError);
      }
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
        function(product){
          if (typeof(data.id) == "undefined") { 
          scope.newOrderFormProductList.push(product); // create product
          } else {
            // Load Updated Product into view
          }
          handleRestCreate("Produkt:","Das Produkt wurde erfolgreich erstellt");
          el.trigger("reset"); // Reset Form
          $("#controlProductIndex > a").trigger("click"); // Switch Tabs
        },  // Success
        handleRestError // Error
        );
    },
    /** Fills the Create Product form with the clicked product of product view **/
    editProduct: function(scope, el, ev) {
      var n = this.newProduct;
      n.attr("id",scope.id);
      n.attr("sku",scope.sku);
      n.attr("units",scope.units);
      n.attr("unit_unit",scope.unit_unit);
      n.attr("name",scope.name);
      n.attr("merchant_id",scope.merchant_id);
      n.attr("comment",scope.comment);
      n.attr("weight",scope.weight);
      n.attr("price",scope.price);
      n.attr("weight_per_unit",scope.weight_per_unit);
      n.attr("product_state_id",scope.product_state_id);
      n.attr("product_type_id",scope.product_type_id);
      $('#createProductForm_unit_unit').val(scope.unit_unit);
      $('#createProductForm_product_state_id').val(scope.product_state_id);
      $('#createProductForm_product_type_id').val(scope.product_type_id);
      $('#createProductForm_merchant_id').val(scope.merchant_id);
      $('#priceInclTax').html('+'+$("#createProductForm_product_type_id").children(':selected').data('tax')+'% MwSt.');
      $('#priceInclTax').data('factor',(1 + (parseInt($("#createProductForm_product_type_id").children(':selected').data('tax'))/100)));
      this.currentProductForm.attr("titleCaption",this.updateProductForm.titleCaption);
      this.currentProductForm.attr("showOrderAndSave",this.updateProductForm.showOrderAndSave);
      this.currentProductForm.attr("buttonCaption",this.updateProductForm.buttonCaption);
      this.currentProductForm.attr("buttonName",this.updateProductForm.buttonName);
      $(".tab-pane.active").removeClass('active');
      $("#productCreate").addClass('active');
    },
    calculateOrderPrice: function(scope, el, ev) {
      /** CALC THE PRICE **/
      var amount = parseFloat($("#newOrderFormOrderAmount").val());
      if (!isNaN(amount)) {
        var taxrate = 1 + (parseFloat($("#newOrderFormProductTaxrate").data("taxrate")) / 100);
        var price = parseFloat($("#newOrderFormProductPrice").val());
        var netTotal = Number((price * amount).toFixed(2));
        var total = Number((price * taxrate * amount).toFixed(2));
        $('#newOrderFormOrderPriceTotalNet').val(netTotal+" €");
        $('#newOrderFormOrderPriceTotalBrt').val(total+" €");
      } else {
        $('#newOrderFormOrderPriceTotalNet').val("-/-");
        $('#newOrderFormOrderPriceTotalBrt').val("-/-");        
      }
    },
    /** This adds the opportunity to edit the properties of a product in the order-form **/
    enableInput: function(scope,el,ev) {
      if ($(this).hasClass('glyphicon-edit')) {
        $(this).removeClass('glyphicon-edit').addClass("glyphicon-ok");
        enable = el.data("enable").split(",").each(function(i,x){
          $("input[name='"+x+"'], select[name='"+x+"']").removeAttr("readonly");
        });
      } else if($(this).hasClass("glyphicon-ok")) {
        console.log("check");
      }
    },
    toggleProductState: function(scope, el, ev) {
      var val = $(el).data("state");
      scope.attr("standardProduct",(val==3));
      scope.attr("blocked",(val==2));
      scope.attr("product_state_id",parseInt(val));
      scope.save().then(function(){
      },handleRestError);
    },
    quickOrder: function(scope, el, ev) {
      var packages = el.parent().siblings('input').val();
      el.parent().siblings('input').val("");
      var amount = scope.units * packages;
      var order = new Order({"amount":amount,"product_id":scope.id,"order_state_id":3,"merchant_id":scope.merchant_id}).save(
      function(){
        handleRestCreate("Bestellung:","Die Bestellung wurde erfolgreich erstellt");
      },handleRestError);
    },
    performProductsFilter: function() {
      var ps = this.productsScopes;
      var filtered = this.allProducts.filter(function(i,x,l){
        if (ps.attr("product_state_id") != "all" && ps.attr("product_state_id") != i.product_state_id) return false;
        if (ps.attr("merchant_id") != "all" && ps.attr("merchant_id") != i.merchant_id) return false;
        return true;
      });
      this.products.replace(filtered);
    },
    performReloadList: function() {
      var merchantId = (this.productsScopes.attr("merchant_id") == "all") ? 0 : this.productsScopes.attr("merchant_id");
      var term = this.productsScopes.attr("term");
      var allProducts = this.allProducts;
      var scope = this;
      if (!term.length) allProducts.replace(new Product.List({}));
      else ProductSearch.findAll({merchantId:merchantId,term:term}).then(function(data,xhr){
        allProducts.replace(JSON.parse(data));
        scope.performProductsFilter();
      },handleRestError);
    },
    /**
     * Filters the list by a number of scopes.
     * Scope filter values are saved in this.productScopes
     */
    filterProducts: function(scope, el, ev) {
      if(!this.allProducts.length) this.allProducts.replace(this.products); // Initialize backup list if needed
      var ps = this.productsScopes;
      var filterScope = $(el).data("scope");
      var filter = $(el).data("filter");
      if (filterScope == "product_state_id" && filter == "standard") filter = 3;
      if (ps.attr("scope") != filter) {
        ps.attr(filterScope,filter); // Replace new filterScope with old filterScope
        if (filterScope == "merchant_id" && ps.attr("term").length) this.performReloadList();
        this.performProductsFilter();
      }
    },
    searchProducts: function(scope, el, ev) {
      if (this.productsScopes.attr('term') != $(el).val()) {
        this.productsScopes.attr("term",$(el).val());
        this.performReloadList();
      }
    }
  }
});

var template = can.view("appMustache");
$("body").html(template);