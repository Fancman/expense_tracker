<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Console\Command;

class CreateRepeatingTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create repeating transactions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
		$transactions = Transaction::whereNotNull('repeating')->get();

		$repeats = [
			'denne',
			'týždenne',
			'mesačne',
			'ročne',			
		];

		foreach ($transactions as $transaction) {

			if( in_array($transaction->repeating, $repeats) ){				
				$transaction_time = Carbon::parse($transaction->transaction_time);
				$now_time = Carbon::now();

				if( $transaction->repeating === 'denne' )
				{
					$transaction_time = $transaction_time->addDay();
				}
				else if( $transaction->repeating === 'týždenne' )
				{
					$transaction_time = $transaction_time->addWeek();
				}
				else if( $transaction->repeating === 'mesačne' )
				{
					$transaction_time = $transaction_time->addMonth();
				}
				else if( $transaction->repeating === 'ročne' )
				{
					$transaction_time = $transaction_time->addYear();
				}

				if($now_time->gte($transaction_time)){

					$new_transaction = $transaction->replicate();

					$new_transaction->created_at = Carbon::now();				
					$new_transaction->updated_at = Carbon::now();
					$new_transaction->transaction_time = $transaction_time;
					$new_transaction->repeating = $transaction->repeating;

					$new_transaction->save();

					$default_currency = User::where('user_id', $new_transaction->user->id)->latest()->first()->currency->id;

					$transaction_items = TransactionItem::where('transaction_id', $transaction->id)->get();

					$transaction_items = [];
					$transaction_sell_items = [];

					foreach ($transaction_items as $transaction_item) {

						if( $transaction->transactionType->id == 4 ){

							$transaction_sell_items[] = [
								'item_type_id' => $transaction_item->item_type_id,
								'transaction_item_name' => $transaction_item->name,
								'quantity' => $transaction_item->quantity,
								'price' => $transaction_item->price,
								'currency_id' => $transaction_item->currency_id,
								'fees' => ($transaction_item->fees ?? 0),
								'fees_currency_id' => $transaction_item->fees_currency_id,
							];
						}else{
							$transaction_items[] = [
								'item_type_id' => $transaction_item->item_type_id,
								'name' => $transaction_item->name,
								'quantity' => $transaction_item->quantity,
								'price' => $transaction_item->price,
								'currency_id' => $transaction_item->currency_id,
								'fees' => ($transaction_item->fees ?? 0),
								'fees_currency_id' => $transaction_item->fees_currency_id,
							];
						}			
					}

					$new_transaction->createTransactionItems(
						$new_transaction->transactionType->code,
						$default_currency,
						$transaction_sell_items,
						$transaction_items,
						$dept_paid = false
					);

					$transaction->repeating = null;
					$transaction->save();
				}
			}
		}

        return 0;
    }
}
