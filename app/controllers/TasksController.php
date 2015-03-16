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
	 * Show the form for editing the specified resource.
	 * GET /tasks/{id}/edit
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
	 * PUT /tasks/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
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