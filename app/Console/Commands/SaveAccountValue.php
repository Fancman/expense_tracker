<?php

namespace App\Console\Commands;

use App\Models\User;
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
		$users = User::all();
		
		foreach ($users as $user) {
			$user_value = $user->calculate_user_value();

			$previous_account_value_record = AccountValues::where('user_id', $user->id)->latest()->first();

			if( $previous_account_value_record == null && $user_value == 0 ){
				continue;
			}

			$account_value = new AccountValues;
			$account_value->user_id = $user->id;
			$account_value->value = $user_value;
			$account_value->current_value = $user_value;
			$account_value->save();
		}

        return 0;
    }
}
