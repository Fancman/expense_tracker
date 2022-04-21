<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\AccountItem;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

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

			$account_item->updateStockItemPriceFromAPI();
			$this->job_tries_count++;	
		}

		$account_items = AccountItem::with('itemType')
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

			$account_item->updateCryptoItemPriceFromAPI();		
			$this->job_tries_count++;	
		}

		if($user_id){
			$user = User::find($user_id);
			$user->fetching_prices = false;
			$user->save();
		}else{
			User::all()->update(['fetching_prices' => false]);
		}
		
    }
}
