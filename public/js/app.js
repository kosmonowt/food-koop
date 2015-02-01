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
  destroy: 'DELETE '+sUrl+'orders/{id}'
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

var ProductSearch = can.Model.extend({
  findAll: function(params) {
    return $.get(sUrl+"products/search/"+params.merchantId+"/"+params.term);
  },
  findOne: 'GET '+sUrl+'products/{id}',
}, {});

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

can.mustache.registerHelper('quickEdit',
  function(subject, verb, number, options){
});

var handleRestError = function(error) {
  console.log(error.statusText);
  console.log(error.responseText);
};