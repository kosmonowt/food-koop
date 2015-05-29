<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function login() {
		View::share('title', "Biokiste Login");
		$this->layout->content = View::make('authentication.login');
	}

	public function dashboard() {
		View::share("title","MyBiokiste");
		View::share("controller","dashboard");
		View::share("member",Member::with("user")->find(Auth::user()->member_id));
		View::share("kontostand",MemberLedger::balance()->own()->first()->balance);
		View::share("articles",	Content::where("type_id","=",2)->where("is_published","=",1)->ordered()->get());
		$Ledger = MemberLedger::starteinlage()->own()->first();
		$starteinlage = ($Ledger) ? $Ledger->balance : 0;
		View::share("starteinlage",$starteinlage);
		View::share("ledger",MemberLedger::own()->latest()->get());
		View::share("user",Auth::user());
		View::share("myself",Auth::user());
		$this->layout->content = View::make("dashboard");
	}

}
