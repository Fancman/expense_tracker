<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

	private $user_id;

	protected $fillable = [
        'name',
		'user_id',
		'category_id',
		'currency_id',
		'address_book_id',
		'source_account_id',
		'end_account_id',
		'transaction_time',
        'value',
		'repeating',
		'transaction_type_id',
    ];

	public function __construct()
	{
		$this->user_id = (auth()->user() ? auth()->user()->id : 4);
	}

	public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

	public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

	public function transactionType()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type_id');
    }

	public function addressBook()
    {
        return $this->belongsTo(AddressBook::class, 'address_book_id');
    }

	public function sourceAccount()
    {
        return $this->belongsTo(Account::class, 'source_account_id');
    }

	public function endAccount()
    {
        return $this->belongsTo(Account::class, 'end_account_id');
    }

	public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

	public function deleteTransaction($type){
		if($type === 'PREDAJ'){
			$this->deleteAccountItemsPredaj();
			$this->deleteTransactionItems();
			$this->delete();
		}else if($type === 'NAKUP'){
			$this->deleteAccountItemsNakup();
			$this->deleteTransactionItems();
			$this->delete();
		}
	}

	public function deleteTransactionItems(){
		$transaction_items = $this->transactionItems();

		if( $transaction_items ){
			$transaction_items->delete();
		}
	}

	public function increaseCash($currency_id, $quantity, $price){
		$total_cash = floatval($quantity) * floatval($price);

		$finance_item = AccountItem::where('account_id', $this->sourceAccount->id)
		->where('item_type_id', 3)
		->where('currency_id', intval($currency_id))
		->latest()->first();

		if( isset($finance_item) ){

			$finance_item = AccountItem::firstOrNew(
				[
					'account_id' => $this->sourceAccount->id,
					'item_type_id' => 3,
					'currency_id' => intval($currency_id)
				],
				[
					'name' => 'Peniaze',
					'item_type_id' => 3,
					'quantity' => $total_cash,
					'average_buy_price' => 1,
					'current_price' => 1,
				]
			);

			$finance_item->save();
		}else{
			$finance_item->quantity = (floatval($finance_item->quantity) + floatval($total_cash));

			$finance_item->save();
		}
	}

	public function decreaseCash($currency_id, $quantity, $price){
		$total_cash = floatval($quantity) * floatval($price);

		$finance_item = AccountItem::where('account_id', $this->sourceAccount->id)
		->where('item_type_id', 3)
		->where('currency_id', intval($currency_id))
		->latest()->first();

		if( isset($finance_item) ){

			$finance_item = AccountItem::firstOrNew(
				[
					'account_id' => $this->sourceAccount->id,
					'item_type_id' => 3,
					'currency_id' => intval($currency_id)
				],
				[
					'name' => 'Peniaze',
					'item_type_id' => 3,
					'quantity' => (0 - $total_cash),
					'average_buy_price' => 1,
					'current_price' => 1,
				]
			);

			$finance_item->save();
		}else{
			$finance_item->quantity = (floatval($finance_item->quantity) - floatval($total_cash));

			$finance_item->save();
		}
	}

	public function deleteAccountItemsPredaj(){
		$transaction_items = $this->transactionItems;
		$source_account = $this->sourceAccount;
		
		if( $transaction_items ){

			foreach ($transaction_items as $transaction_item) {
				$name = $transaction_item->name;
				$price = $transaction_item->price;
				$quantity = $transaction_item->quantity;
				$currency_id = $transaction_item->currency->id;
				$id = $transaction_item->id;

				$account_item = AccountItem::where('name', $name)
				->where('account_id', $this->sourceAccount->id)->latest()->first();

				if( $account_item->count() == 0 ){
					$account_item = AccountItem::create(
					[
						'account_id' => $this->sourceAccount->id,
						'item_type_id' => $transaction_item['item_type_id'],
						'currency_id' => $transaction_item['currency_id'],
						'name' => $transaction_item['name'],
						'quantity' => $transaction_item['quantity'],
						'average_buy_price' => $transaction_item['price'],
						'current_price' => $transaction_item['price'],
					]);

					$source_account->value = floatval($source_account->value) + (floatval($price) * floatval($quantity));
					$source_account->save();
				}
				else if ($quantity < $account_item->quantity)
				{
					$latest_transaction = $this->getLatestPrice($id);

					$account_item->average_buy_price = (floatval($account_item->average_buy_price) + floatval($price)) / 2;
					$account_item->quantity = (floatval($account_item->quantity) + floatval($quantity));
					$account_item->current_price = $latest_transaction->price;
					$account_item->save();	
				}

				$this->decreaseCash($currency_id, $quantity, $price);	
			}
		}
	}

	public function deleteAccountItemsNakup(){
		$transaction_items = $this->transactionItems;
		$source_account = $this->sourceAccount;
		
		if( $transaction_items ){

			foreach ($transaction_items as $transaction_item) {
				$name = $transaction_item->name;
				$price = $transaction_item->price;
				$quantity = $transaction_item->quantity;
				$currency_id = $transaction_item->currency->id;
				$id = $transaction_item->id;

				$account_item = AccountItem::where('name', $name)
				->where('account_id', $this->sourceAccount->id)->latest()->first();

				if($account_item){
					if($quantity == $account_item->quantity)
					{
						$account_item->delete();
					}
					else if ($quantity < $account_item->quantity)
					{
						$latest_transaction = $this->getLatestPrice($id);

						$account_item->average_buy_price = (floatval($account_item->average_buy_price) + floatval($price)) / 2;
						$account_item->quantity = (floatval($account_item->quantity) - floatval($quantity));
						$account_item->current_price = $latest_transaction->price;
						$account_item->save();	
					}

					$source_account->value = floatval($source_account->value) - (floatval($price) * floatval($quantity));
					$source_account->save();
				}

				$this->increaseCash($currency_id, $quantity, $price);	
			}
		}
	}

	public function getLatestPrice($id){
		return DB::table('transaction_items')
		->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
		->where('transactions.user_id', $this->user_id)
		->where('transaction_items.id', '!=' , $id)
		->select('transaction_items.price')
		->latest('transaction_items.created_at')->first();
	}
	
}
