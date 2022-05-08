<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\AccountItem;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class UpdatePrices implements ShouldQueue//, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $user;
	protected $job_tries_count = 0;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user->withoutRelations();
    }

	/**
	 * Get the middleware the job should pass through.
	 *
	 * @return array
	 */
	public function middleware()
	{
		return [new WithoutOverlapping($this->user->id)];
	}


	/*public function uniqueId()
    {
        return $this->user->id;
    }*/
	

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

		$user_id = $this->user->id;

        $account_items = AccountItem::with('itemType')
		->where('invalid_name', '!=', 1)
		->when($user_id, function($query, $user_id) {
			return $query->whereRelation('account', 'user_id', $user_id);
		})
		->whereRelation('itemType', 'code', 'AKCIA')		
		->get();
		
		foreach ($account_items as $account_item) {
			if( $this->job_tries_count >= 5 ){
				sleep(60);
				$this->job_tries_count = 0;
			}

			$result = $account_item->updateStockItemPriceFromAPI();

			if( $result == null ){
				$account_item->invalid_name = true;
				$account_item->save();
				
				continue;
			}

			$this->job_tries_count++;	
		}

		$account_items = AccountItem::with('itemType')
		->where('invalid_name', '!=', 1)
		->when($user_id, function($query, $user_id) {
			return $query->whereRelation('account', 'user_id', $user_id);
		})
		->whereRelation('itemType', 'code', 'KRYPTOMENA')		
		->get();
		
		foreach ($account_items as $account_item) {
			if( $this->job_tries_count >= 5 ){
				sleep(60);
				$this->job_tries_count = 0;
			}

			$result = $account_item->updateCryptoItemPriceFromAPI();	
			
			if( $result == null ){
				$account_item->invalid_name = true;
				$account_item->save();

				continue;
			}

			$this->job_tries_count++;	
		}

		if($user_id){
			$user = User::find($user_id);
			$user->fetching_prices = false;
			$user->save();
		}else{
			User::all()->update(['fetching_prices' => false]);
		}

		Artisan::queue('command:recount_account_values');

		sleep(60);		
    }
}
