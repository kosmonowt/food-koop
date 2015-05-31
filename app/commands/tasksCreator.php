<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class tasksCreator extends Command {

		/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'tasks:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Erstellt Tasks (Dienstplaneinträge) für die nächsten xxx Tage.';

	/**
	 * Task types grouped by weekday
	 **/
	protected $taskTypes = null;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	protected function configureDay($plusDays) {

		$day = new DateTime("today +".$plusDays." days");

		$this->info('Creating Tasks for '.$day->format("Y-m-d"));
		
		if (array_key_exists($day->format('w'), $this->taskTypes)) {
	
			$dayTasks = Task::day($day->format("Y-m-d"))->get();
			
			$taskTypes = $this->taskTypes[$day->format("w")];

			foreach($taskTypes as $taskType) {
				if ( ( ($taskType->datetime_published_start <= $day) && ($taskType->datetime_published_stop >= $day) || // IF now lays inbetween both times
					 ($taskType->datetime_published_start >= $day) && ($taskType->datetime_published_stop >= $day) || // IF now lays before stop date and start is also in future
					 ($taskType->datetime_published_stop <= $day) && ($taskType->datetime_published_start <= $day) &&  ($taskType->datetime_published_start > $taskType->datetime_published_stop) ) // IF now lays after start and stop AND start is after stop (already started again)
					 && $taskType->active) {
					// NOW WE CAN ASSIGN A TASK
					// LOOK FOR ALL TASKS ON THIS DAY. AND
					
					// Only create if not already created.
					$taskExists = false;
					foreach ($dayTasks as $dayTask) if ($dayTask->task_type_id == $taskType->id) $taskExists = true;
					if ($taskExists) {
						$this->info('Task '.$taskType->name." EXISTING. SKIPPED.");
						continue;
					}


					$task = new Task();
					$task->task_type_id = $taskType->id;
					$task->date = $day->format("Y-m-d");
					$task->start = $taskType->time_start;
					$task->stop = $taskType->time_stop;
					$task->save();
					$this->info('Task '.$taskType->name." created");

				} else {
					// REMOVE IF NEEDED FROM CALENDAR
				}
			}
		} else {
			$this->info('Nothing found for today.');
		}


	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		
		$countDays = 150;
		$taskTypes = TaskType::get();
		// Organise the taskTypes by WeekDay
		foreach ($taskTypes as $taskType) {
			// $table->string("repeat_days",2);
			// $table->time("time_start")->nullable();
			// $table->time("time_stop")->nullable();
			// $table->date("published_start")->nullable();
			// $table->date("published_stop")->nullable();

			// Create Datetime objects for every task type for less overhead in processing
			$taskType->datetime_published_start = ($taskType->published_start != "0000-00-00" && !is_null($taskType->published_start)) ? new DateTime($taskType->published_start) : new DateTime ("yesterday");
			$taskType->datetime_published_stop = ($taskType->published_stop != "0000-00-00" && !is_null($taskType->published_stop)) ? new DateTime($taskType->published_stop) : new DateTime ("now +$countDays days ");

			$this->taskTypes[$taskType->day_of_week][] = $taskType;
		}
		
		if (!count($this->taskTypes)) $this->info("No TASKS in List. Please, first add tasks!");
		else {

			for ($i = 1; $i <= $countDays; $i++) {
				$this->configureDay($i);
			}

		}

		$this->info("Finished.");

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
//			array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
//			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
