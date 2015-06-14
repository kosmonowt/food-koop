<?php
class MemberLedgerController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return MemberLedger::get()->toJson();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()	{

		$data = Input::all();
		$ledger = new MemberLedger();
		$ledger->fill($data);
		if(!$ledger->save()) {
			App::abort(403,$ledger->getErrors());
		}

		return $ledger->toJson();		
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
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$p = Merchant::find($id);
		$p->fill(Input::all());

		return ($p->save()) ? "true" : "false";
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$p = Merchant::find($id);
		return ($p->delete()) ? "true" : "false";
	}


}