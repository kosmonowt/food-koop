<?php

class OrdersController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return Order::with("member")->with("product")->with("merchant")->orderBy('updated_at', 'DESC')->take(50)->get()->toJson();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = Input::all();

		$product = Product::findOrFail($data['product_id']);
		$merchant = Merchant::findOrFail($data['merchant_id']);

		$order = new Order();
		$order->fill($data);
		
		if ($order->amount < 1) return App::abort(403, 'Bitte gebe die Menge an, die Du bestellen willst.');

		$member_id = Auth::user()->member_id;
		$user_id = Auth::user()->id;

		$order->member_id = $member_id;
		$member = Member::find($member_id);

		$order->user_id = $user_id;
		
		// TODO: Check if can do better.
		$order->order_state_id = 0;

		$order->save();

		$order->product = $product;
		$order->merchant = $merchant;
		$order->member = $member;

		return $order->toJson();
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$order = Order::find($id);
		$order->fill(Input::all());

		return ($order->save()) ? "true" : "false";
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$order = Order::find($id);
		return ($order->delete()) ? "true" : "false";
	}


}
