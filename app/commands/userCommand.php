<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class userCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'biokiste:user';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Gives control on console user-modification.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		if (!$this->option("username") && !$this->option("id")) die("Kein Username oder ID eingegeben");
		
		if ($this->option("id")) {
			$user = User::findOrFail($this->option("id"));
		} else {
			$user = User::where("username","LIKE",$this->option("username"))->first();
			if (!$user) die("User not found");
		}

		$this->info("User: ".$user->firstname." ".$user->lastname);

		if ($this->argument("promote")) {
			$this->info("Promoting User");
			$user->user_group_id = 3;
		} elseif ($this->argument("unpromote")) {
			$user->user_group_id = 1;
		}

		if ($this->option("password")) {
			$user->password = Hash::make($this->option("password"));
			$this->info("Setting new password.");
		}

		var_dump($user->save());


		
		$this->info("Changes applied.");

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('promote', InputArgument::OPTIONAL, 'Make this User Superadmin.'),
			array('unpromote', InputArgument::OPTIONAL, 'Make this User Ordinary.'),
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
			array('id', null, InputOption::VALUE_REQUIRED, "Id", null),
			array('username', null, InputOption::VALUE_REQUIRED, "Username", null),
			array('password', null, InputOption::VALUE_REQUIRED, "Password", null)
		);
	}

}
