<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get("login",function(){
	View::share('title', "Biokiste Login");
	return View::make('authentication.login');
});

Route::post("login",function(){
	Auth::attempt(array('username' => Input::get("name"), 'password' => Input::get("password")));
	return Redirect::intended("dashboard.html");
});

// Master Routes for HTML-Pages
Route::get("/",function(){return Redirect::to("dashboard.html");});
Route::get("logout",function(){Auth::logout(); return Redirect::to("login");});
Route::get("dashboard.html",array("before"=>"auth","uses" => "HomeController@dashboard"));
Route::get("{controller}.html", array('before' => "auth", function($controller) {
	View::share("title","Biokiste App");
	View::share("controller",$controller);
	View::share("myself",Auth::user());
	return View::make("app.$controller");
}));

// This all returns JSON
Route::get("products/mostPopular",function(){ return Product::mostPopular()->take(20)->get();});
Route::get('products/search/{merchantId}/{term}','ProductsController@search');
Route::get('products/count/{merchantId?}/{term?}','ProductsController@searchCount');

// Special Routes for Order Views
Route::get('orders/byProduct/{product_id?}/{order_state?}','OrdersController@byProduct');
Route::get("orders/bulk/applyOrder","OrdersController@orderBulk");
Route::post("orders/bulk/{product_id}/{order_state_id}",'OrdersController@updateBulk');
Route::post("orders/bulk/{order_state_id}","OrdersController@updateBulk");
Route::get('users/byMember/{id}', function($id){ return User::where("member_id",$id)->get()->toJson(); });

// Special Dashboard Routes
Route::get("memberLedger/starteinlage", function(){ return MemberLedger::starteinlage()->own()->first()->toJson(); });
Route::get("memberLedger/balance", function(){ return MemberLedger::balance()->own()->first()->toJson(); });
Route::get("memberLedger/member/{member_id}",function($member_id){ return MemberLedger::from($member_id)->ordered()->get()->toJson();});

Route::get('orders/marketplace',function(){ return Order::marketplace()->get()->toJson();});
Route::get('orders/my',"OrdersController@my");

Route::get("tasks/byWeek/{number?}/{taskType?}","TasksController@byWeek");
Route::get("tasks/upcoming","TasksController@upcomingUnassigned");
Route::put("tasks/upcoming/{id}","TasksController@assign");
Route::get("tasks/my","TasksController@my");
Route::put("tasks/my/{id}","TasksController@myUndo");

Route::get("members/myself",function(){ return User::with("member")->where("id","=",Auth::user()->id)->first()->toJson(); });

// General Resource Routes
Route::resource('members', 'MembersController');
Route::resource('memberGroups', 'MemberGroupsController');
Route::resource('memberStatus', 'MemberStatusController');
Route::resource('memberLedger', 'MemberLedgerController');
Route::resource('merchants', 'MerchantsController');
Route::resource('orders', 'OrdersController');
Route::resource('orderStates', 'OrderStatesController');
Route::resource('products', 'ProductsController');
Route::resource('productTypes', 'ProductTypesController');
Route::resource("tasks","TasksController");
Route::resource("taskTypes","TaskTypesController");
Route::resource('users', 'UsersController');
Route::resource("userGroups", "UserGroupsController");


Route::get("test/{id}",function($id){return Order::where("product_id","=",$id)->open()->count();});

