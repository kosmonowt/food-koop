<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class TaskCreator extends Command {

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
		$day = new datetime(strtotime("today +$plusDays"));
		$dayTasks = Task::day($day->format("Y-m-d"))->get();
		$taskTypes = $this->taskTypes[$day->format("w")];
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		
		$taskTypes = TaskType::get();
		// Organise the taskTypes by WeekDay
		foreach ($taskTypes as $taskType) $this->taskTypes[$taskType->day_of_week] = $taskType;

		$countDays = 150;
		for ($i = 1; $i <= $countDays; $i++) {
			$this->configureDay($i);
		}

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('example', InputArgument::REQUIRED, 'An example argument.'),
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
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
