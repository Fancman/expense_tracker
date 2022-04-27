<?php

namespace App\Console\Commands;

use App\Models\AccountItem;
use App\Models\AccountValues;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SaveAccountValue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:save_account_values';

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
		$account_values = AccountItem::select(DB::raw('users.id, SUM(quantity * current_price) as total_account_value'))
		->join('accounts', 'account_items.account_id', '=', 'accounts.id')
		->join('users', 'accounts.user_id', '=', 'users.id')
		->groupBy('users.id')
		->get();

		foreach($account_values as $account_value){
			$total_account_value = $account_value->total_account_value;
			$user_id = $account_value->id;

			$account_value = new AccountValues;
			$account_value->user_id = $user_id;
			$account_value->value = $total_account_value;
			$account_value->save();
		}

		

        return 0;
    }
}
