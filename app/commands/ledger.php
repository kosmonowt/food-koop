<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ledger extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'ledger:deduct';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Deducts the current member-fee from the ledger';

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
		$deductionInterval = ledgerDeductionInterval;
		$deductionFee = ledgerDeductionFee;

		$force = $this->option("force");
		$test = $this->option("perform");

		if ( $force || ( (((intval(date("m"))+2) % $deductionInterval) == 0) && ((intval(date("d")) == 1)) ) ) {
			
			$this->info("Deduction happens.");

			$m = Member::paying()->get();

			foreach($m as $member) {
				$this->info("Processing: (".$member->id.") ".$member->name.".");
				$newLedgerBalance = $member->ledgerBalance - $deductionFee;
				$this->info("Balance: €".$member->ledgerBalance." => €".$newLedgerBalance);

				if ($test) {
					$memberLedger = new MemberLedger();
					$memberLedger->vwz = "Mitgliedsbeitrag ".date("m/Y");
					$memberLedger->member_id = $member->id;
					$memberLedger->balance = 0 - $deductionFee;
					$memberLedger->date = date("Y-m-d");
					$memberLedger->save();
					$this->info("New Balance Saved.");
				}

			}

		} else {
			$this->info("Nothing to deduct.");
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
			//array('example', InputArgument::REQUIRED, 'An example argument.'),
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
			array('force', null, InputOption::VALUE_NONE, 'Perform even if the date is not first of month.'),
			array('perform', null, InputOption::VALUE_NONE, 'Safety. Add this option to perform transaction.'),
		);
	}

}
