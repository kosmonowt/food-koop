/*======================================================================*/
/*============================ CONFIGURATION ===========================*/
/*======================================================================*/
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

/*======================================================================*/
/*===================== LIBRARY OF COMMON SNIPS ========================*/
/*======================================================================*/
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

Date.prototype.toDateInputValue = (function() {
    var local = new Date(this);
    local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
    return local.toJSON().slice(0,10);
});

/**
 * Converts a datetime (mysql) - string to a date
 */
function datefromdatetime(datetime) {
  if (typeof(datetime) == "undefined") return "";

  var parts = datetime.substr(0,10).split('-');
  var d = new Date(parts[0], parts[1]-1, parts[2]);
  tototo = d;
  return ('0'+d.getDate()).slice(-2)+"."+('0'+(d.getMonth()+1)).slice(-2)+"."+d.getFullYear();
}

function removeAllAttr(canMap) {
  for (var attr in canMap) canMap.removeAttr(attr);
  return canMap;
}

function replaceAllAttr(canMap,oCanMap) {
  for (var attr in oCanMap) canMap.attr("attr",oCanMap.attr(attr));
  return canMap;
}

/*======================================================================*/
/*============================ REST API SECTION ========================*/
/*======================================================================*/

/** ORDERS **/
var Order = can.Model.extend({
  findAll: 'GET '+sUrl+'orders',
  findOne: 'GET '+sUrl+'orders/{id}',
  update: 'PUT '+sUrl+'orders/{id}',
  destroy: 'DELETE '+sUrl+'orders/{id}',
  create : 'POST '+sUrl+'orders'
}, {});

/**
 * For Order By Product. Can handle Params
 * first should be the product_id.
 * if not provided is is interpreted as order_state.
 * more information see at routes.php
 */
var OrderSearch = new can.Model();
OrderSearch.findByProduct = function(params) {
  var param = "";
  for (var i=0;i < params.length;i++) param += "/"+params[i];
  return $.get(sUrl+'orders/byProduct'+param);
};

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

var UserGroup = can.Model.extend({
  findAll: 'GET '+sUrl+'userGroups',
  findOne: 'GET '+sUrl+'userGroups/{id}',
  update: 'PUT '+sUrl+'userGroups/{id}',
  destroy: 'DELETE '+sUrl+'userGroups/{id}'
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

var MemberLedger = can.Model.extend({
  create :'POST '+sUrl+'memberLedger'
});

var Product = can.Model.extend({
  findAll: 'GET '+sUrl+'products',
  findOne: 'GET '+sUrl+'products/{id}',
  update: 'PUT '+sUrl+'products/{id}',
  destroy: 'DELETE '+sUrl+'products/{id}',
  create : 'POST '+sUrl+'products'  
}, {});

var WeekList = can.Model.extend({
  findAll: 'GET '+sUrl+'tasks/byWeek'
});

var Task = can.Model.extend({
  findAll: 'GET '+sUrl+'tasks',
  findOne: 'GET '+sUrl+'tasks/{id}',
  update: 'PUT '+sUrl+'tasks/{id}',
  destroy: 'DELETE '+sUrl+'tasks/{id}',
  create : 'POST '+sUrl+'tasks'  
}, {});

var TaskType = can.Model.extend({
  findAll: 'GET '+sUrl+'taskTypes',
  findOne: 'GET '+sUrl+'taskTypes/{id}',
  update: 'PUT '+sUrl+'taskTypes/{id}',
  destroy: 'DELETE '+sUrl+'taskTypes/{id}',
  create : 'POST '+sUrl+'taskTypes'  
}, {});

/*======================================================================*/
/*============================ MUSTACHE SECTION ========================*/
/*======================================================================*/

can.mustache.registerHelper("posNeg",function(data){
  var balance = data.context.balance;
  return (parseFloat(balance) > 0) ? "text-info" : "text-danger";
});

can.mustache.registerHelper("dmY",function(data){
  var date = eval("data.context."+data.hash.field);
  return datefromdatetime(date);
});

/** Forms a String HH:ii:ss into HH:ii **/
can.mustache.registerHelper("timeHI",function(data){
  var val = eval("data.context."+data.hash.field);
  return (typeof(val) == "string") ? val.substr(0,5) : "";
});

/** Parses a Date-Range for Table view **/
can.mustache.registerHelper("publishedStartStop",function(data){
  var pStart = data.context.published_start;
  var pStop = data.context.published_stop;
  var out = "";

  if (pStart == "0000-00-00" || typeof(pStart) == "object") out += "-/-";
  else if (pStart != "0000-00-00") out += datefromdatetime(pStart);

  out += "&nbsp;-<br>";

  if (pStop == "0000-00-00" || typeof(pStart) == "object") out += "-/-";
  else if (pStop != "0000-00-00") out += datefromdatetime(pStop);

  return can.mustache.safeString(out);
});

/** Used in Orders to point out if one or more dates to be parsed for the cumulated order */
can.mustache.registerHelper("oneOrTwoDates",function(data){
  if (typeof (eval("data.context."+data.hash.field1)) == "undefined") return "";
  var date1 = eval("data.context."+data.hash.field1+".substr(0,10)");
  var date2 = eval("data.context."+data.hash.field2+".substr(0,10)");
  var ret = "";
  if (date1 != date2) {
    ret = datefromdatetime(date1); 
    ret += " und ";
  }
  ret += datefromdatetime(date2);
  return ret;
});

can.mustache.registerHelper("colormark",function(data){
  var quote = (parseInt(this.totalAmount) / parseInt(this.units) * 100);
  var classname = "demand-";
  if (quote >= 100) { classname += "max";
  } else if (quote >= 80) { classname += "80";
  } else if (quote >= 60) { classname += "60";
  } else if (quote >= 50) { classname += "50";
  } else if (quote >= 30) { classname += "30";
  } else if (quote >= 25) { classname += "25";
  } else { classname += "low"; }
  return classname;
});

can.mustache.registerHelper("orderIsPending",function(data){
  return (data.context.order_state_id < 3);
});
can.mustache.registerHelper("orderIsOnList",function(data){
  return (data.context.order_state_id == 3);
});
can.mustache.registerHelper("orderIsWaiting",function(data){
  return (data.context.order_state_id == 4);
});

can.mustache.registerHelper("dowName",function(data){
   var days = ["Sonntag","Montag","Dienstag","Mittwoch","Donnerstag","Freitag","Samstag","Sonntag"];
   return days[data.context.day_of_week];
});

/*======================================================================*/
/*======================= RESPONSE INTERPRETERS ========================*/
/*======================================================================*/

/**
 * This one gets some parameters and renders a message that comes into the flashContainer on the page.
 * Used for RestERRORS and Success Messages for example.
 */
function addFlashMessage(title, message, type) {
  $('#flashContainer').html("<div class='row bg-"+type+" flashMessage' onclick='$(this).remove();'><div class='col-xs-12'><strong>"+title+"</strong>&nbsp;"+message+"</div></div>");
  setTimeout(function(){ $("#flashContainer").html(""); }, 10000);
}
// Some shortcuts for addFlashMessage
var handleRestCreate = function(title,message) { addFlashMessage(title,message,"success");}
var handleRestDestroy = function(title,message) { addFlashMessage(title,message,"warning");}
var handleRestUpdate = function(title,message) { addFlashMessage(title,message,"info");}

/**
 * This gets a restError Message String and parses for a flash Message.
 * Can always be used for restError Handling
 * adds it to a flash message
 **/
var handleRestError = function(error) {
  //console.log(error.statusText);
  var responseText = JSON.parse(error.responseText);
  var errorMessage = responseText.error.message;
  var msg = "";
  // Can handle JSON and STRING
  try {
    // JSON?
    var msgArr = JSON.parse(errorMessage);
    for (var property in msgArr) if (msgArr.hasOwnProperty(property)) eval("msg = msg+'<br>'+msgArr."+property);
  } catch(e) { 
    // STRING!
    msg = errorMessage; 
  } 
  addFlashMessage("Fehler:",msg,"danger");
};


/*======================================================================*/
/*======================= UI ACTIONS AND EVENTS ========================*/
/*======================================================================*/

function gotoIndex() {
  $("#tabIndexControl").trigger("click");
}

/**
 * show: id (without hash) of element to show
 * affects: group of elements (including that one with id) to hide
 * see example in orders.blade.php and orders.js
 **/
function tabToggler(show,affects) {
  $("#"+show).removeClass("hidden");
  $("."+affects).not("#"+show).addClass('hidden');
}