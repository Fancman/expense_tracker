<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\AccountItem;
use App\Models\AccountValues;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RecountAccountValues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:recount_account_values';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		$accounts = Account::all();

		foreach ($accounts as $account) {

			$calc = $account->calculate_current_value();

			$account->value = $calc['total_value'];
			$account->current_value = $calc['current_value'];

			$account->save();
		}

        return 0;
    }
}
