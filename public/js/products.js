/*======================================================================*/
/*================== COMPONENT CONTROLLER SECTION ======================*/
/*======================================================================*/

can.Component.extend({
  tag: 'products-app',

  viewModel: function () {
    return {
      paginate: new Paginate({ page: 1 }),
      quickOrder: {
        order: function(e,el) {
          var packages = el.parent().siblings('input').val();
          el.parent().siblings('input').val("");
          var amount = e.attr("units") * packages;
          var order = new Order({"amount":amount,"product_id":e.attr("id"),"order_state_id":3,"merchant_id":e.attr("merchant_id")}).save(
          function(){
            handleRestCreate("Bestellung:","Die Bestellung wurde erfolgreich erstellt");
            e.attr("countOrders",e.attr("countOrders")+1);
          },handleRestError);
        }
      },
      productsList: function () {
        var params = {
          page: this.attr('paginate.current_page'),
          };
        var productsList = Product.findAll(params);
        var self = this;
        productsList.then(function (products) {
          self.attr('paginate.last_page', products.last_page);
          self.attr('paginate.current_page', products.attr("current_page"));
        });
        return productsList;
      }
    }
  },

  // scope: {
  //   merchants: new Merchant.List({}), // Merchants existing
  //   productTypes: new ProductType.List({}), // Product Types existing
  //   products: this.viewModel(),
  //   /** Is called when New Product is being Clicked **/
  //   resetCreateProductForm: function(b,el,ev) {
  //     removeAllAttr(this.newProduct);
  //     $("#productCreate form").trigger("reset");
  //     replaceAllAttr(this.currentProductForm,this.createProductForm);
  //   },
  //   /** Removes a Product from the database **/
  //   deleteProduct: function(product) {
  //     var products = this.products;
  //     if (confirm("Soll dieses Produkt wirklich aus der Datenbank entfernt werden?")) {
  //       var p = new Product(product);
  //       var id = p.attr("id");
  //       p.destroy().then(function(){
  //         products.replace(products.filter(function(i,x,l){return i.attr("id") != id;}));
  //         handleRestDestroy("Gelöscht:","Das Produkt wurde aus der Datenbank entfernt.");
  //       },handleRestError);
  //     } 
  //   },
  //   createProduct: function(scope, el, ev) {
  //     ev.preventDefault();
  //     var data = {};
  //     el.find("input, select, button").each(function(i,x){
  //       eval("data."+$(this).attr("name")+" = '"+$(this).val()+"';"); // Save Data
  //     });
  //     var product = new Product(data);
  //     product.save(
  //       function(product){
  //         if (typeof(data.id) == "undefined") { 
  //         scope.newOrderFormProductList.push(product); // create product
  //         } else {
  //           // Load Updated Product into view
  //         }
  //         handleRestCreate("Produkt:","Das Produkt wurde erfolgreich erstellt");
  //         el.trigger("reset"); // Reset Form
  //         $("#controlProductIndex > a").trigger("click"); // Switch Tabs
  //       },  // Success
  //       handleRestError // Error
  //       );
  //   },
  //   /** Fills the Create Product form with the clicked product of product view **/
  //   editProduct: function(scope, el, ev) {
  //     $(".tab-pane.active").removeClass('active');
  //     $("#productCreate").addClass('active');
  //   },
  //   toggleProductState: function(scope, el, ev) {
  //     var val = $(el).data("state");
  //     scope.attr("standardProduct",(val==3));
  //     scope.attr("blocked",(val==2));
  //     scope.attr("product_state_id",parseInt(val));
  //     scope.save().then(function(){
  //       },handleRestError);
  //   },
  //   quickOrder: function(scope, el, ev) {
  //     var packages = el.parent().siblings('input').val();
  //     el.parent().siblings('input').val("");
  //     var amount = scope.units * packages;
  //     var order = new Order({"amount":amount,"product_id":scope.id,"order_state_id":3,"merchant_id":scope.merchant_id}).save(
  //     function(){
  //       handleRestCreate("Bestellung:","Die Bestellung wurde erfolgreich erstellt");
  //     },handleRestError);
  //   },
  //   performProductsFilter: function() {
  //     var ps = this.productsScopes;
  //     var filtered = this.allProducts.filter(function(i,x,l){
  //       if (ps.attr("product_state_id") != "all" && ps.attr("product_state_id") != i.product_state_id) return false;
  //       if (ps.attr("merchant_id") != "all" && ps.attr("merchant_id") != i.merchant_id) return false;
  //       return true;
  //     });
  //     this.products.replace(filtered);
  //   },
  //   performReloadList: function() {
  //     var merchantId = (this.productsScopes.attr("merchant_id") == "all") ? 0 : this.productsScopes.attr("merchant_id");
  //     var term = this.productsScopes.attr("term");
  //     var allProducts = this.allProducts;
  //     var scope = this;
  //     if (!term.length) allProducts.replace(new Product.List({}));
  //     else ProductSearch.findAll({merchantId:merchantId,term:term}).then(function(data,xhr){
  //       allProducts.replace(JSON.parse(data));
  //       scope.performProductsFilter();
  //     },handleRestError);
  //   },
  //   /**
  //    * Filters the list by a number of scopes.
  //    * Scope filter values are saved in this.productScopes
  //    */
  //   filterProducts: function(scope, el, ev) {
  //     if(!this.allProducts.length) this.allProducts.replace(this.products); // Initialize backup list if needed
  //     var ps = this.productsScopes;
  //     var filterScope = $(el).data("scope");
  //     var filter = $(el).data("filter");
  //     if (filterScope == "product_state_id" && filter == "standard") filter = 3;
  //     if (ps.attr("scope") != filter) {
  //       ps.attr(filterScope,filter); // Replace new filterScope with old filterScope
  //       if (filterScope == "merchant_id" && ps.attr("term").length) this.performReloadList();
  //       this.performProductsFilter();
  //     }
  //   },
  //   searchProducts: function(scope, el, ev) {
  //     if (this.productsScopes.attr('term') != $(el).val()) {
  //       this.productsScopes.attr("term",$(el).val());
  //       this.performReloadList();
  //     }
  //   },
  // }
});

can.Component.extend({
  tag: "grid",
  viewModel: {
    items: [],
    waiting: true
  },
  template: "<table>"+productGridHead+"<tbody><content></content></tbody></table>",
  events: {
    init: function () { 
      this.update(); 
    },
    "{viewModel} deferreddata": "update",
    update: function () {
      var deferred = this.viewModel.attr('deferreddata'),
        viewModel = this.viewModel,
        el = this.element;
      if (can.isDeferred(deferred)) {
        this.viewModel.attr("waiting", true);
        deferred.then(function (items) {
          viewModel.attr('items').replace(items);
        });
      } else {
        viewModel.attr('items').attr(deferred, true);
      }
    },
    "{items} change": function () { 
      this.viewModel.attr("waiting", false); 
    }
  }
});

var template = can.view("appMustache");
$("body").html(template);