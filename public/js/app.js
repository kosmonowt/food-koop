/** CONFIG **/
var sUrl = "/app/";
var appConfig = {
  product: {
    search: {
      term: {
        autocompleteLength : 3 // Begin to search with Autocomplete
      }
    }
  }
};
var globals = {};

/** PUT TO LIB SOON **/
function ucfirst(str) {
  //  discuss at: http://phpjs.org/functions/ucfirst/
  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // bugfixed by: Onno Marsman
  // improved by: Brett Zamir (http://brett-zamir.me)
  //   example 1: ucfirst('kevin van zonneveld');
  //   returns 1: 'Kevin van zonneveld'

  str += '';
  var f = str.charAt(0)
    .toUpperCase();
  return f + str.substr(1);
}

/** END LIB **/


/** ORDERS **/
var Order = can.Model.extend({
  findAll: 'GET '+sUrl+'orders',
  findOne: 'GET '+sUrl+'orders/{id}',
  update: 'PUT '+sUrl+'orders/{id}',
  destroy: 'DELETE '+sUrl+'orders/{id}',
  create : 'POST '+sUrl+'orders'  
}, {});

/** ORDER STATE **/
var OrderState = can.Model.extend({
  findAll: 'GET '+sUrl+'orderStates',
  findOne: 'GET '+sUrl+'orderStates/{id}',
  update: 'PUT '+sUrl+'orderStates/{id}',
  destroy: 'DELETE '+sUrl+'orderStates/{id}'
}, {});

var Merchant = can.Model.extend({
  findAll: 'GET '+sUrl+'merchants',
  findOne: 'GET '+sUrl+'merchants/{id}',
  update: 'PUT '+sUrl+'merchants/{id}',
  destroy: 'DELETE '+sUrl+'merchants/{id}'
}, {});

var ProductType = can.Model.extend({
  findAll: 'GET '+sUrl+'productTypes',
  findOne: 'GET '+sUrl+'productTypes/{id}',
  update: 'PUT '+sUrl+'productTypes/{id}',
  destroy: 'DELETE '+sUrl+'productTypes/{id}'
}, {});

var ProductSearch = new can.Model();
ProductSearch.findAll = function(params) {return $.get(sUrl+"products/search/"+params.merchantId+"/"+params.term);};
ProductSearch.findOne = 'GET '+sUrl+'products/{id}';

var User = can.Model.extend({
  findAll: 'GET '+sUrl+'users',
  findOne: 'GET '+sUrl+'users/{id}',
  update: 'PUT '+sUrl+'users/{id}',
  destroy: 'DELETE '+sUrl+'users/{id}',
  create : 'POST '+sUrl+'users'
}, {});

var Member = can.Model.extend({
  findAll: 'GET '+sUrl+'members',
  findOne: 'GET '+sUrl+'members/{id}',
  update: 'PUT '+sUrl+'members/{id}',
  destroy: 'DELETE '+sUrl+'members/{id}',
  create : 'POST '+sUrl+'members'
}, {});

var MemberGroup = can.Model.extend({
  findAll: 'GET '+sUrl+'memberGroups',
  findOne: 'GET '+sUrl+'memberGroups/{id}',
  update: 'PUT '+sUrl+'memberGroups/{id}',
  destroy: 'DELETE '+sUrl+'memberGroups/{id}'
}, {});

var MemberStatus = can.Model.extend({
  findAll: 'GET '+sUrl+'memberStatus',
  findOne: 'GET '+sUrl+'memberStatus/{id}',
  update: 'PUT '+sUrl+'memberStatus/{id}',
  destroy: 'DELETE '+sUrl+'memberStatus/{id}'
}, {});

var Product = can.Model.extend({
  findAll: 'GET '+sUrl+'products',
  findOne: 'GET '+sUrl+'products/{id}',
  update: 'PUT '+sUrl+'products/{id}',
  destroy: 'DELETE '+sUrl+'products/{id}',
  create : 'POST '+sUrl+'products'  
}, {});

can.mustache.registerHelper('quickEdit',
  function(subject, verb, number, options){
});

function addFlashMessage(title, message, type) {
  $('#flashContainer').html("<div class='row bg-"+type+" flashMessage' onclick='$(this).remove();'><div class='col-xs-12'><strong>"+title+"</strong>&nbsp;"+message+"</div></div>");
  setTimeout(function(){ $("#flashContainer").html(""); }, 10000);
}

var handleRestError = function(error) {
  //console.log(error.statusText);
  var responseText = JSON.parse(error.responseText);
  var errorMessage = responseText.error.message;
  // TODO MAKE FIELD TO PROVIDE ERROR MESSAGE TO USER.
  addFlashMessage("Fehler:",errorMessage,"danger");
};

var handleRestCreate = function(title,message) {
  addFlashMessage(title,message,"success");
}

var handleRestDestroy = function(title,message) {
  addFlashMessage(title,message,"warning");
}

var handleRestUpdate = function(title,message) {
  addFlashMessage(title,message,"info");
}

function gotoIndex() {
  $("#tabIndexControl").trigger("click");
}
