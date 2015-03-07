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
	/* DO REAL AUTHENTIFICATION HERE ASAP */
	return Redirect::intended("dashboard.html");
});

Route::get("logout",function(){Auth::logout(); return Redirect::to("login");});
Route::get("dashboard.html",array("before"=>"auth","uses" => "HomeController@dashboard"));
Route::get("{controller}.html", array('before' => "auth", function($controller) {
	View::share("title","Biokiste App");
	View::share("controller",$controller);
	return View::make("app.$controller");
}));


Route::get('products/search/{merchantId}/{term}','ProductsController@search');
Route::get('products/marketplace',function(){ return Order::marketplace()->get();});
Route::get('orders/byProduct/{product_id?}/{order_state?}','OrdersController@byProduct');
Route::post("orders/bulk/{product_id}/{order_state_id}",'OrdersController@updateBulk');
Route::post("orders/bulk/{order_state_id}","OrdersController@updateBulk");
Route::get("orders/bulk/applyOrder","OrdersController@orderBulk");

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

