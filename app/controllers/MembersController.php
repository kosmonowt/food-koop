<?php

class MembersController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if (Auth::user()->isAdmin) {
			$m = Member::with("user")->with("memberGroup")->with("ledgerBalance");
		} else {
			$m = Member::with("user")->with("memberGroup");
		}
		

		return $m->get()->toJson();
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
		$member = new Member();
		$member->fill($data);
		if (!$member->save()) {
			App::abort(403,$member->getErrors());			
		}
		return $member->toJson();
		//
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
		$Member = Member::find($id);
		$Member->fill(Input::all());

		return ($Member->save()) ? "true" : "false";
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$Member = Member::find($id);
		return ($Member->delete()) ? "true" : "false";
	}


}
