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

Route::get('/', function()
{
	return View::make('hello');
});

Route::get('products/search/{merchantId}/{term}','ProductsController@search');
Route::resource('products', 'ProductsController');

Route::resource('merchants', 'MerchantsController');
Route::resource('orders', 'OrdersController');
Route::resource('orderStates', 'OrderStatesController');

Route::resource('members', 'MembersController');
Route::resource('memberGroups', 'MemberGroupsController');
Route::resource('memberStatus', 'MemberStatusController');

Route::get('users/byMember/{id}', function($id){ return User::where("member_id",$id)->get()->toJson(); });

Route::resource('users', 'UsersController');
