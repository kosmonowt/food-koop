<?php

class TasksController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /tasks
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Display a listing of the resource of upcoming free tasks for dashboard view
	 * 
	 */
	public function upcoming() { return Task::upcoming()->with("taskType")->dayAsc()->get()->toJson(); }
	
	public function upcomingUnassigned() { return Task::upcoming()->unassigned()->with("taskType")->dayAsc()->get()->toJson(); }

	/**
	 * Assigns a task to the current user
	 */
	public function assign($id) {
		$task = Task::findOrFail($id);
		$task->member_id = Auth::user()->member_id;
		
		if (!$task->save()) App::abort(403,$task->getErrors());

		return $task->toJson();		
	}

	/**
	 * Display a listing of all tasks with current member_id
	 */
	public function my() { return Task::own()->upcoming()->dayAsc()->with("taskType")->get()->toJson();}

	/**
	 * Remove a task assignment (only when the current user applies to a task that he is assigned to)
	 */
	public function myUndo($id) {
		$task = Task::findOrFail($id);
		
		if (!$task->member_id == Auth::user()->member_id) App::abort(403,"Fehler: Du bist nicht für diesen Dienst eingetragen.");
		
		$task->member_id = null;
		Event::fire("tasks.unassign",$task);

		if (!$task->save()) App::abort(403,$task->getErrors());


		return $task->toJson();				
	}

	/**
	 * Produces a calendar-like listing of the current tasks.
	 * Segmentated by weeks and days
	 */
	public function byWeek($startWeek = null, $taskType = null) {
		$weeksToShow = 4;
		$daysInWeek = 7;
		// Generate days
		$weeks = new Collection();
		$daysOfWeek = new Collection();
		$i = 0;

		$tasks = Task::untilDay(date("Y-m-d",strtotime("monday this week +".($weeksToShow*$daysInWeek)." days")))->with("TaskType")->get();
		
		while ($i < ($weeksToShow*7)) {
			$dayTs = strtotime("monday this week +$i days");

		
			$day = new Model();
			$day->id = intval(date("Yz",$dayTs));
			$day->date = date("Y-m-d",$dayTs);
			$day->day_of_week = date("w",$dayTs);
			$day->task = new Collection();

			foreach($tasks as $task) if ($task->date == $day->date) $day->task->add($task);

			$daysOfWeek->add($day);

			$i++;
		
			if (! ($i % 7)) {
				$week = new Model();
				$week->id = intval(date("YW",$dayTs));
				$week->number = date("W",$dayTs);
				$week->year = date("Y",$dayTs);	
				$week->days = $daysOfWeek;
				
				$weeks->add($week);
				
				$daysOfWeek = new Collection();

			}
		}
		
		return $weeks->toJson();

	}

	/**
	 * Show the form for creating a new resource.
	 * GET /tasks/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /tasks
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /tasks/{id}
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
	 * PUT /tasks/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$input = Input::all();

		$task = Task::findOrFail($id);

		if ($input['member_id'] == "") $input['member_id'] = null;

		$task->fill($input);

		
		if (!$task->save()) App::abort(403,$taskType->getErrors());

		return $task->toJson();
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /tasks/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}