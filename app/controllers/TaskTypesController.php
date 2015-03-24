<?php

class TaskTypesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /tasktypes
	 *
	 * @return Response
	 */
	public function index()	{ return TaskType::with("memberGroup")->orderBy("day_of_week","ASC")->orderBy("time_start","ASC")->get()->toJson();	}

	/**
	 * Show the form for creating a new resource.
	 * GET /tasktypes/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /tasktypes
	 *
	 * @return Response
	 */
	public function store()
	{
		$taskType = new TaskType();
		$taskType->fill(Input::all());

		if(!$taskType->save()) {
			App::abort(403,$taskType->getErrors());
		}

		return $taskType->toJson();
	}

	/**
	 * Display the specified resource.
	 * GET /tasktypes/{id}
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
	 * PUT /tasktypes/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$t = TaskType::findOrFail($id);

		$t->fill(Input::all());

		if(!$t->save()) {
			App::abort(403,$t->getErrors());
		}

		return $t->toJson();
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /tasktypes/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$t = TaskType::findOrFail($id);

		return ($t->delete()) ? "true" : "false";
	}

}