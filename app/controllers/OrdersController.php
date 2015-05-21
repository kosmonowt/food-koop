<?php

class OrdersController extends \BaseController {


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()	{
		return Order::with("member")->with("product")->with("merchant")->orderBy('updated_at', 'DESC')->take(50)->get()->toJson();
	}

	/**
	 * 
	 * This will be the pill that 
	 * 
	 **/
	public function countByOrderState($order_state_id,$by_product = false) {
	}

	public function byProduct($product_id = null,$order_state = null) {
		
		// If product id is a string we have an order state passed here...
		if (!intval($product_id) && !is_null($product_id)) {
			$order_state = $product_id;
			$product_id = null;
		}

		$orders = Order::byProductVerbose();
		if (!is_null($product_id)) $orders->where("product_id","=",$product_id);
		if (!is_null($order_state)) {
			switch ($order_state) {
				case 'waiting':
					$orders->waiting();
					break;
				case 'listed':
					$orders->listed();
					break;
				case "pending":
					$orders->pending();
					break;
				case "complete":
					$orders->completed();
					break;
			}
		}
		$orders->orderBy("demand","DESC");
		$orders->orderBy("earliestOrder","ASC");
		return $orders->get()->toJson();
	}

	/**
	 * This function is called when the user changes the state of order(s)
	 **/
	public function updateBulk($product_id,$order_state_id = null) {
		
		if (is_null($order_state_id)) {
			$productIds = Input::get("product_ids");
			$order = Order::whereIn("product_id",$productIds);
			$order_state_id = $product_id;
		} else {
			$order = Order::where("product_id","=",$product_id);
		}
		
		if ($order_state_id == 4) {
			// Apply only for orders waiting.
			$order->where("order_state_id","<",4);
		} elseif ($order_state_id == 5) {
			// Apply only for orders pending
			$order->where("order_state_id","=",4);
		} elseif ($order_state_id == 3) {
			// Apply only for orders pending or ordered (not for completed ones)
			$order->where("order_state_id","<=",5);
		} elseif ($order_state_id == 100) {
			// Set completed
			$order->where("order_state_id","=",5);
		}

		if (!is_null(Input::get("latestOrder"))) $order->where("created_at","<=",Input::get("latestOrder"));
		if (!is_null(Input::get("earliestOrder"))) $order->where("created_at",">=",Input::get("earliestOrder"));
		Event::fire("orders.setState",array($order->get(),$order_state_id));

		$order->update(array("order_state_id" => $order_state_id));

		return $order->get()->toJson();
	}

	/**
	 * This function is called when the user presses "Bestellung jetzt auslösen".
	 **/
	public function orderBulk() {
		$orderStateId = 5;
		Event::fire("orders.setState",array(Order::where("order_state_id","=",4)->get(),$orderStateId));
		$order = Order::where("order_state_id","=",4)->update(array("order_state_id" => $orderStateId));
		return "{\"result\":\"$order\"}";
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()	{

		$data = Input::all();

		$product = Product::findOrFail($data['product_id']);

		if ($product->blocked) return App::abort(403, "Dieses Produkt ist gesperrt und kann nicht bestellt werden."); //" (".$product->comment.")";
		
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
		$order->order_state_id = 1;
		if (!is_null(Input::get("order_state_id"))) {
			// Authentification here, as well
			$order->order_state_id = Input::get("order_state_id");
		}


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

	public function my() {
		return Order::my()->open()->with("product")->get()->toJson();
	}

	public function exportForOrderGroup() {

		$merchants = Merchant::get();

		Excel::create(date("Y-m-d")."_bestellung",function($excel) use ($merchants) {

			$excel->setTitle("Bestellungen vom ".date("d.m.Y"));
			$excel->setCreator("Biokiste Website Backend");
			$excel->setDescription("Biokiste Bestellungswebsite");
			
			foreach ($merchants as $merchant) {
			
				$orders = $merchant->orders()->byProductVerbose()->where("orders.order_state_id","=",4)->get();
				if (count($orders)) {
 
 					$excel->sheet('Bestellung bei '.$merchant->name, function($sheet) use ($orders,$merchant) {
 						View::share("merchant",$merchant);
 						View::share("orders",$orders);
 						$sheet->loadView("tables.order");
 						//$sheet->fromModel($orders, null, 'A4', true,false);
					});

				}

			}

		})->export("xlsx");

	}

	public function exportForCommissioner() {

		$merchants = Merchant::get();

		Excel::create(date("Y-m-d")."_bestellung",function($excel) use ($merchants) {

			$excel->setTitle("Bestellungen vom ".date("d.m.Y"));
			$excel->setCreator("Biokiste Website Backend");
			$excel->setDescription("Biokiste Bestellungswebsite");
			
			foreach ($merchants as $merchant) {
			
				$orders = $merchant->orders()->with("member")->with("product")->where("orders.order_state_id","=",4)->orderBy("product_id","ASC")->get();
				if (count($orders)) {
 
 					$excel->sheet('Bestellung bei '.$merchant->name, function($sheet) use ($orders,$merchant) {
 						View::share("merchant",$merchant);
 						View::share("orders",$orders);
 						$sheet->loadView("tables.commissioner");
					});

				}

			}

		})->export("xlsx");

	}

}