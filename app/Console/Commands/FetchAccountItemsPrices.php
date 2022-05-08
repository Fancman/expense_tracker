<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Jobs\UpdatePrices;
use App\Models\AccountItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FetchAccountItemsPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fetch_prices';

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

			$account_items = AccountItem::whereRelation('account.user', 'id', $user->id)->get();

			$count = $account_items->count();

			if($count == 0){
				continue;
			}

			$user->fetching_prices = true;
			$user->save();
			
			UpdatePrices::dispatch($user);	
		}

        return 0;
    }
}
