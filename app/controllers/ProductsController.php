<?php

class ProductsController extends \BaseController {

	


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return Product::orderCount()->get()->toJson();
	}

	public function search($merchantId, $term) {
		$Product = Product::with("product_type");
		$Product->where("sku","LIKE","$term%");
		$Product->orWhere("name","LIKE","$term%");

		if($merchantId > 0) $Product->where("merchant_id",$merchantId);

		return $Product->get()->toJson();
	}

	public function searchCount($merchantId = null, $term = null) {
		$Product = Product::with("product_type");
		$Product->where("sku","LIKE","$term%");
		$Product->orWhere("name","LIKE","$term%");

		if($merchantId > 0) $Product->where("merchant_id",$merchantId);

		return $Product->count()->toJson();		
	}

	public function mostWanted() {
	}

	public function standards() {

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
		$data["sku"] = str_replace(" ","",$data['sku']);

		// Product State setting
		if (isset($data['product_state_id'])) {
			// @todo: Auth first. by now skipping
			// Only Admin can change that in first place.
			$product_state_id = 1;
			unset($data['product_state_id']);
		} else {
			$product_state_id = 1;
		}

		$Product = new Product();
		$Product->fill($data);
		$Product->product_state_id = $product_state_id;

		if(!$Product->save()) {
			App::abort(403,$Product->getErrors());
		}

		if (isset($data['order_amount']) && isset($data['createAndOrder'])) {
			$Order = new Order();
			$Order->product_id = $Product->id;
			$Order->member_id = Auth::user()->member_id;
			$Order->user_id = Auth::user()->id;
			$Order->merchant_id = $data['merchant_id'];
			// Todo: Check if can do better
			$Order->order_state_id = 0;
			$Order->amount = $data['order_amount'];
			$Order->save();
		}

		return $Product->toJson();
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

		$p = Product::find($id);
		$p->fill(Input::all());

		$isAdmin = 1;

		if ($isAdmin && Input::get("product_state_id")) {
			// Todo: Change only for admins
			$p->product_state_id = Input::get("product_state_id");
		}

		return ($p->save()) ? $p->toJson(); : "false";
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$p = Product::find($id);
		return ($p->delete()) ? "true" : "false";
	}


}
