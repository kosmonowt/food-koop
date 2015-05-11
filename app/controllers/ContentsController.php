<?php

class ContentsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /contents
	 *
	 * @return Response
	 */
	public function index()
	{
		return Content::get()->toJson();
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /contents/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /contents
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = Input::all();

		$content = new Content();
		$content->fill($data);
		$content->created_by = Auth::user()->id;
		$content->permalink = sha1(time());

		if(!$content->save()) {
			App::abort(403,$content->getErrors());
		}

		return $content->toJson();
	}

	/**
	 * Display the specified resource.
	 * GET /contents/{id}
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
	 * PUT /contents/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$content = Content::find($id);
		$content->fill(Input::all());

		if(!$content->save()) {
			App::abort(403,$content->getErrors());
		}

		return ($content->save()) ? "true" : "false";		
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /contents/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$Content = Content::find($id);
		return ($Content->delete()) ? "true" : "false";
	}

}