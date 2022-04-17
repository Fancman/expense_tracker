<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Budget;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Console\Command;

class CheckBudgets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check_budgets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check category budgets';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		$budgets = Budget::all();

		$budget_periods = [
			'deň',
			'týždeň',
			'mesiac',
			'rok',
		];

		foreach ($budgets as $budget) {

			if( in_array($budget->budget_period, $budget_periods) ){

				$start_time = Carbon::parse($budget->start_time);
				$now_time = Carbon::now();

				if( $budget->repeating === 'deň' )
				{
					$end_start_time = $start_time->addDay();
				}
				else if( $budget->repeating === 'týždeň' )
				{
					$end_start_time = $start_time->addWeek();
				}
				else if( $budget->repeating === 'mesiac' )
				{
					$end_start_time = $start_time->addMonth();
				}
				else if( $budget->repeating === 'rok' )
				{
					$end_start_time = $start_time->addYear();
				}

				if( $now_time->gte($end_start_time) ){
					$budget->start_time = $end_start_time;
					$budget->reached = 0;
					
					$budget->save();
				}
			}
		}

		$this->info('The command was successful!');

        return 0;
    }
}
