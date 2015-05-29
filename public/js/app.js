/*======================================================================*/
/*============================ CONFIGURATION ===========================*/
/*======================================================================*/
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

/*!
 * CanJS - 2.1.4
 * http://canjs.us/
 * Copyright (c) 2014 Bitovi
 * Fri, 21 Nov 2014 22:25:59 GMT
 * Licensed MIT
 * Includes: can/map/sort
 * Download from: http://canjs.com
 */
(function(undefined) {

    // ## map/sort/sort.js
    var __m1 = (function(can) {

        // Change bubble rule to bubble on change if their is a comparator
        var oldBubbleRule = can.List._bubbleRule;
        can.List._bubbleRule = function(eventName, list) {
            if (list.comparator) {
                return "change";
            }
            return oldBubbleRule.apply(this, arguments);
        };
        if (can.Model) {
            var oldModelListBubble = can.Model.List._bubbleRule;
            can.Model.List._bubbleRule = function(eventName, list) {
                if (list.comparator) {
                    return "change";
                }
                return oldModelListBubble.apply(this, arguments);
            };
        }

        var proto = can.List.prototype,
            _changes = proto._changes,
            setup = proto.setup;

        //Add `move` as an event that lazy-bubbles

        // extend the list for sorting support

        can.extend(proto, {
            comparator: undefined,
            sortIndexes: [],


            sortedIndex: function(item) {
                var itemCompare = item.attr(this.comparator),
                    equaled = 0;
                for (var i = 0, length = this.length; i < length; i++) {
                    if (item === this[i]) {
                        equaled = -1;
                        continue;
                    }
                    if (itemCompare <= this[i].attr(this.comparator)) {
                        return i + equaled;
                    }
                }
                return i + equaled;
            },


            sort: function(method, silent) {
                var comparator = this.comparator,
                    args = comparator ? [

                        function(a, b) {
                            a = typeof a[comparator] === 'function' ? a[comparator]() : a[comparator];
                            b = typeof b[comparator] === 'function' ? b[comparator]() : b[comparator];
                            return a === b ? 0 : a < b ? -1 : 1;
                        }
                    ] : [method];
                if (!silent) {
                    can.trigger(this, 'reset');
                }
                return Array.prototype.sort.apply(this, args);
            }
        });
        // create push, pop, shift, and unshift
        // converts to an array of arguments
        var getArgs = function(args) {
            return args[0] && can.isArray(args[0]) ? args[0] : can.makeArray(args);
        };
        can.each({

                push: "length",

                unshift: 0
            },

            function(where, name) {
                var proto = can.List.prototype,
                    old = proto[name];
                proto[name] = function() {
                    // get the items being added
                    var args = getArgs(arguments),
                        // where we are going to add items
                        len = where ? this.length : 0;
                    // call the original method
                    var res = old.apply(this, arguments);
                    // cause the change where the args are:
                    // len - where the additions happened
                    // add - items added
                    // args - the items added
                    // undefined - the old value
                    if (this.comparator && args.length) {
                        this.sort(null, true);
                        can.batch.trigger(this, 'reset', [args]);
                        this._triggerChange('' + len, 'add', args, undefined);
                    }
                    return res;
                };
            });
        //- override changes for sorting
        proto._changes = function(ev, attr, how, newVal, oldVal) {
            if (this.comparator && /^\d+./.test(attr)) {
                // get the index
                var index = +/^\d+/.exec(attr)[0],
                    // and item
                    item = this[index];
                if (typeof item !== 'undefined') {
                    // and the new item
                    var newIndex = this.sortedIndex(item);
                    if (newIndex !== index) {
                        // move ...
                        [].splice.call(this, index, 1);
                        [].splice.call(this, newIndex, 0, item);
                        can.trigger(this, 'move', [
                            item,
                            newIndex,
                            index
                        ]);
                        can.trigger(this, 'change', [
                            attr.replace(/^\d+/, newIndex),
                            how,
                            newVal,
                            oldVal
                        ]);
                        return;
                    }
                }
            }
            _changes.apply(this, arguments);
        };
        //- override setup for sorting
        proto.setup = function(instances, options) {
            setup.apply(this, arguments);
            if (this.comparator) {
                this.sort();
            }
        };
        return can.Map;
    })(window.can, undefined);

})();

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
  canMap.each(function(i,n){canMap.removeAttr(n);});
  return canMap;
}

function replaceAllAttr(canMap,oCanMap) {
  oCanMap.each(function(i,n){canMap.attr(n,oCanMap.attr(n));});
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

var MyOrder = can.Model.extend({
  findAll: 'GET '+sUrl+"orders/my",
  destroy: 'DELETE '+sUrl+'orders/{id}'
}, {});

var Marketplace = can.Model.extend({
  findAll: 'GET '+sUrl+"orders/marketplace",
  create: 'POST '+sUrl+''
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

var Myself = can.Model.extend({
  findOne: 'GET '+sUrl+'members/myself',
  update: 'PUT '+sUrl+'members/{id}',
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

var UpcomingTask = can.Model.extend({
  findAll: "GET "+sUrl+"tasks/upcoming",
  update: "PUT "+sUrl+"tasks/upcoming/{id}"
  },{});

var MyTask = can.Model.extend({
  findAll: "GET "+sUrl+"tasks/my",
  update: "PUT "+sUrl+"tasks/my/{id}"
},{});


var TaskType = can.Model.extend({
  findAll: 'GET '+sUrl+'taskTypes',
  findOne: 'GET '+sUrl+'taskTypes/{id}',
  update: 'PUT '+sUrl+'taskTypes/{id}',
  destroy: 'DELETE '+sUrl+'taskTypes/{id}',
  create : 'POST '+sUrl+'taskTypes'  
}, {});

var Content = can.Model.extend({
  findAll: 'GET '+sUrl+'contents',
  findOne: 'GET '+sUrl+'contents/{id}',
  update:  'PUT '+sUrl+'contents/{id}',
  destroy: 'DELETE '+sUrl+'contents/{id}',
  create:  'POST '+sUrl+'contents'
}, {});

var DashboardContent = can.Model.extend({
  findAll: 'GET '+sUrl+'dashboard'
}, {});

var ContentType = can.Model.extend({
  findAll: 'GET '+sUrl+'contentTypes',
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
  return (data.context.order_state_id < 4);
});
can.mustache.registerHelper("orderIsOnList",function(data){
  return (data.context.order_state_id == 4);
});
can.mustache.registerHelper("orderIsWaiting",function(data){
  return (data.context.order_state_id == 5);
});

can.mustache.registerHelper("dowName",function(data){
   var days = ["Sonntag","Montag","Dienstag","Mittwoch","Donnerstag","Freitag","Samstag","Sonntag"];
   return days[data.context.day_of_week];
});

/**
 * Returns the calculated price for an order from amount and taxes and product price (for product)
 **/
can.mustache.registerHelper("orderPrice",function(data){
  return parseFloat(parseInt(data.context.amount) * parseFloat(data.context.product.price) * (1+ (data.context.product.taxrate/100)),2).toFixed(2);
});

/**
 * Returns the calculated prace for an order from taxes and product price (for marketplace model)
 **/
can.mustache.registerHelper("marketplacePrice",function(data){
  return parseFloat(parseFloat(data.context.price) * (1+ (data.context.product_type.tax / 100)),2).toFixed(2);
});

/**
 * Returns the calculated price for an order from taxes and product price
 **/
can.mustache.registerHelper("productPrice",function(data){
  return parseFloat(parseFloat(data.context.product.price) * (1+ (data.context.product.taxrate/100)),2).toFixed(2);
});

can.mustache.registerHelper("toFixed",function(data){
  return parseFloat().toFixed(2);
});


can.mustache.registerHelper("optList",function(data){
  console.log(data);
});

/*======================================================================*/
/*======================= RESPONSE INTERPRETERS ========================*/
/*======================================================================*/

/**
 * This one gets some parameters and renders a message that comes into the flashContainer on the page.
 * Used for RestERRORS and Success Messages for example.
 */
function addFlashMessage(title, message, type) {
  $('#flashContainer').html("<div class='alert alert-"+type+" alert-dismissalbe' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Schließen'><span aria-hidden='true'>&times;</span></button><strong>"+title+"</strong>&nbsp;"+message+"</div>");
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