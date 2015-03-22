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
	$user = User::find(1);
//	Auth::login($user);
// var_dump(Input::get("name"));
// var_dump(Input::get("password"));
// var_dump(Crypt::encrypt(Input::get("password")));
// die;
	Auth::attempt(array('username' => Input::get("name"), 'password' => Input::get("password")));
	return Redirect::intended("dashboard.html");
});

Route::get("logout",function(){Auth::logout(); return Redirect::to("login");});
Route::get("dashboard.html",array("before"=>"auth","uses" => "HomeController@dashboard"));
Route::get("{controller}.html", array('before' => "auth", function($controller) {
	View::share("title","Biokiste App");
	View::share("controller",$controller);
	return View::make("app.$controller");
}));

Route::get("products/mostPopular",function(){ return Product::mostPopular()->take(20)->get();});
Route::get('products/search/{merchantId}/{term}','ProductsController@search');
Route::get('products/count/{merchantId?}/{term?}','ProductsController@searchCount');
Route::get('orders/marketplace',function(){ return Order::marketplace()->get()->toJson();});
Route::get('orders/byProduct/{product_id?}/{order_state?}','OrdersController@byProduct');
Route::post("orders/bulk/{product_id}/{order_state_id}",'OrdersController@updateBulk');
Route::post("orders/bulk/{order_state_id}","OrdersController@updateBulk");
Route::get("orders/bulk/applyOrder","OrdersController@orderBulk");
Route::get('orders/my',"OrdersController@my");

Route::resource('products', 'ProductsController');
Route::resource('productTypes', 'ProductTypesController');

Route::resource('merchants', 'MerchantsController');
Route::resource('orders', 'OrdersController');
Route::resource('orderStates', 'OrderStatesController');

Route::resource('members', 'MembersController');
Route::resource('memberGroups', 'MemberGroupsController');
Route::resource('memberStatus', 'MemberStatusController');

Route::get('users/byMember/{id}', function($id){ return User::where("member_id",$id)->get()->toJson(); });

Route::resource('users', 'UsersController');
Route::resource("userGroups", "UserGroupsController");

Route::get("memberLedger/starteinlage", function(){ return MemberLedger::starteinlage()->own()->first()->toJson(); });
Route::get("memberLedger/balance", function(){ return MemberLedger::balance()->own()->first()->toJson(); });
Route::get("memberLedger/member/{member_id}",function($member_id){ return MemberLedger::from($member_id)->ordered()->get()->toJson();});
Route::resource('memberLedger', 'MemberLedgerController');

Route::get("tasks/byWeek/{number?}/{taskType?}","TasksController@byWeek");
Route::resource("tasks","TasksController");
Route::resource("taskTypes","TaskTypesController");