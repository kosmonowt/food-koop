<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class biokiste extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'biokiste:configure';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'That task can configure the APP. It is used to set settings to the database';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	protected function fireCreate() {
	
		$this->comment("Creating a new setting");

		$key = $this->ask("Key:\t");
		$value = $this->ask("Value:\t");
		
		$this->comment("Now chose a type: i = Integer, f = Float, a = Array, s = String");
		
		$type = null;
		while (is_null($type) || !in_array($type, ["i","f","a","s"])) {
			$type = $this->ask("Type: [i,f,a,s]");
		}
		
		$types = ["i" => "int","f" => "float","s" => "string","a" => "array"];
		$setting = new Setting();
		$setting->key = $key;
		$setting->value = $value;
		$setting->type = $types[$type];
		$setting->save();

		$this->info("Thank You, the setting has been saved.");
	}

	protected function fireDelete($id) {
		$id = intval($id);
		$setting = Setting::find($id);
		
		if (!$setting) $this->error("This setting $id could not be found. Finishing.");
		else {
			$this->comment("You are about to delete a setting. This can result in severe problems with the app.");
			$answer = $this->ask("Do you really want to delete ".$setting->id." - ".$setting->key."? Type 'YES'");
			if ($answer == "YES") {
				$setting->delete();
				$this->info("Successfully DELETED setting.");
			} else {
				$this->info("Aborted.");
			}
		}
	}

	protected function fireUpdate($id) {
		$id = intval($id);
		$setting = Setting::find($id);
		
		if (!$setting) $this->error("This setting $id could not be found. Finishing.");
		else {
			$this->comment("Editing setting $id - ".$setting->key." (".$setting->type.")");
			$this->info("Old value: '".$setting->value."'");
			$newValue = $this->ask("New value: ");
			$setting->value = $newValue;
			$setting->save();
		}

	}

	protected function fireList() {
		$this->info("Showing all current settings.");
		$settings = Setting::get();
		$this->comment(count($settings)." found.\n");
		foreach ($settings as $setting) {
			$this->comment($setting->id.":\t".$setting->key);
			$this->info("Value:\t".$setting->value);
			$this->info("Type:\t".$setting->type);
			$this->info("------------------------------------------------------");
		}

		$this->comment("Do you want to edit one of those settings?");
		$result = $this->ask("If YES, please type the ID of the setting to change. If NO, just press n or leave blank");
		if (!is_null($result) && strtolower($result) != "n" && intval($result) >= 1 ) {
			echo "\n";
			$this->fireUpdate($result);
		}

	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->info("Welcome to the CLI Settings Editor\n");
		if ($this->argument("create") == "create") $this->fireCreate();
		elseif (substr($this->argument("create"),0,7) == "delete=") $this->fireDelete(substr($this->argument("create"),7));
		else $this->fireList();


		$this->info("\nFinished.");
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('create',InputArgument::OPTIONAL, 'Creates a new setting.'),
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
//			array('update', null, InputOption::VALUE_REQUIRED, 'Updates a setting with an id given here.', false),
			array('delete', null, InputOption::VALUE_REQUIRED, 'Deletes a setting with an id given here.', false)
		);
	}

}
