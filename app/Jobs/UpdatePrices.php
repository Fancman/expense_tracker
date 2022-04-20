<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\AccountItem;

class UpdatePrices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $user_id;
	protected $job_tries_count = 0;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		$user_id = $this->user_id;

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
    }
}
