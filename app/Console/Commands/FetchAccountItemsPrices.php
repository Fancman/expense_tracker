<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Jobs\UpdatePrices;
use Illuminate\Console\Command;

class SaveAccountValue extends Command
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

			$user->fetching_prices = true;
			$user->save();
			
			UpdatePrices::dispatch($user);	
		}

        return 0;
    }
}
