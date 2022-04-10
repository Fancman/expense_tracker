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
		}
		else if($type === 'NAKUP'){
			$this->deleteAccountItemsNakup();
			$this->deleteTransactionItems();
			$this->delete();
		}
		else if($type === 'PRIJEM'){
			$this->deleteTransactionPrijem();
			$this->delete();
		}
		else if($type === 'VYDAJ'){
			$this->deleteTransactionVydaj();
			$this->delete();
		}
	}

	public function createTransactionItems($type, $default_currency, $transaction_sell_items, $transaction_items){
		if($type === 'NAKUP'){
			$this->createAccountItemsNakup($transaction_items, $default_currency);
		}
		else if ($type === 'PREDAJ'){
			$this->createAccountItemsPredaj($transaction_sell_items, $default_currency);
		}
		else if($type === 'PRIJEM'){
			$this->createAccountItemsPrijem();
		}
		else if($type === 'VYDAJ'){
			$this->createAccountItemsVydaj();
		}
	}

	public function deleteTransactionItems(){
		$transaction_items = $this->transactionItems();

		if( $transaction_items ){
			$transaction_items->delete();
		}
	}

	public function increaseCash($account, $currency_id, $quantity, $price){
		$total_cash = floatval($quantity) * floatval($price);

		$finance_item = AccountItem::where('account_id', $account->id)
		->where('item_type_id', 3)
		->where('currency_id', intval($currency_id))
		->latest()->first();

		if( !isset($finance_item) ){

			$finance_item = AccountItem::firstOrNew(
				[
					'account_id' => $account->id,
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

	public function decreaseCash($account, $currency_id, $quantity, $price){
		$total_cash = floatval($quantity) * floatval($price);

		$finance_item = AccountItem::where('account_id', $account->id)
		->where('item_type_id', 3)
		->where('currency_id', intval($currency_id))
		->latest()->first();

		if( !isset($finance_item) ){

			$finance_item = AccountItem::firstOrNew(
				[
					'account_id' => $account->id,
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

	public function createAccountItemsVydaj(){
		$finance_items = AccountItem::where('account_id', $this->sourceAccount->id)
		->where('item_type_id', 3)
		->get();			

		foreach ($finance_items as $finance_item) {
			if($finance_item->currency_id == $this->currency->id){
				$finance_item->quantity = (floatval($finance_item->quantity) - floatval($this->value));
				$finance_item->save();
			}
		}

		$account = Account::where('id', $this->sourceAccount->id)->latest()->first();
		$account->value = (floatval($account->value) - floatval($this->value));
		$account->save();
	}

	public function createAccountItemsPrijem(){
		$finance_item = AccountItem::where('account_id', $this->endAccount->id)
		->where('item_type_id', 3)
		->where('currency_id', intval($this->currency->id))
		->latest()->first();

		if ( !isset($finance_item) ){
			$finance_item = AccountItem::firstOrNew(
				[
					'account_id' => $this->endAccount->id,
					'item_type_id' => 3,
					'currency_id' => intval($this->currency->id)
				],
				[
					'name' => 'Peniaze',
					'item_type_id' => 3,
					'quantity' => floatval($this->value),
					'average_buy_price' => 1,
					'current_price' => 1,
				]
			);

			$finance_item->save();
		}else{
			$finance_item->quantity = (floatval($finance_item->quantity) + floatval($this->value));

			$finance_item->save();
		}

		$account = Account::where('id', $this->endAccount->id)->latest()->first();;
		$account->value = (floatval($account->value) + floatval($this->value));
		$account->save();
	}

	public function processTransactionItemData($transaction_item, $type, $default_currency){
		$transaction_item_name_field = '';

		if($type === 'NAKUP'){
			$transaction_item_name_field = 'name';
		} else if($type === 'PREDAJ'){
			$transaction_item_name_field = 'transaction_item_name';
		}
		
		$transaction_item_data = [
			'name' => $transaction_item[$transaction_item_name_field],
			'item_type_id' => $transaction_item['item_type_id'],
			'quantity' => $transaction_item['quantity'],
			'price' => $transaction_item['price'],
			'currency_id' => $transaction_item['currency_id'],
			'fees' => $transaction_item['fees'],
			'fees_currency_id' => $transaction_item['fees_currency_id'],
		];

		// Remove null values
		foreach ($transaction_item_data as $key => $value) {
			if( empty($value) ){
				unset($transaction_item_data[$key]);
			}
		}

		if( !isset($transaction_item_data['currency_id']) ){
			$transaction_item_data['currency_id'] = $default_currency;
		}


		if( !isset($transaction_item_data['fees_currency_id']) ){
			$transaction_item_data['fees_currency_id'] = $default_currency;
		}

		return $transaction_item_data;
	}

	public function createAccountItemsPredaj($transaction_items, $default_currency){
		$price_sum = 0;

		foreach ($transaction_items as $transaction_item) {
			$transaction_item_data = $this->processTransactionItemData($transaction_item, 'PREDAJ', $default_currency);
			
			if ( count($transaction_item_data) == 0 ){
				break;
			}

			$transaction_item_data['transaction_id'] = $this->id;

			TransactionItem::create($transaction_item_data);

			$transaction_item_price = floatval($transaction_item['fees']) + (floatval($transaction_item['quantity']) * floatval($transaction_item['price']));
	
			$account_item = AccountItem::where('account_id', $this->sourceAccount->id)
			->where('name', $transaction_item_data['name'])
			->firstOr(function () {
				return null;
			});

			if( $account_item ){
				$account_item->quantity = (floatval($account_item->quantity) - floatval($transaction_item['quantity']));
				$account_item->average_buy_price = (floatval($account_item->average_buy_price) + floatval($transaction_item['price'])) / 2;
				$account_item->current_price = floatval($transaction_item['price']);

				$account_item->save();
			} else {
				$account_item = AccountItem::create(
					[
						'account_id' => $this->sourceAccount->id,
						'item_type_id' => $transaction_item['item_type_id'],
						'currency_id' => $transaction_item['currency_id'],
						'name' => $transaction_item_data['name'],
						'quantity' => $transaction_item['quantity'],
						'average_buy_price' => $transaction_item['price'],
						'current_price' => $transaction_item['price'],
					]
				);

				$account_item->save();
			}	
			
			// Increase cash on account
			$source_account = $this->sourceAccount;
			
			$this->increaseCash($source_account, $this->currency->id, $transaction_item_price, 1);		

			$price_sum += $transaction_item_price;	
		}
		
		if( $price_sum > 0 ){
			$this->value = $price_sum;				
			$this->save();
		}
	}

	public function createAccountItemsNakup($transaction_items, $default_currency){
		$price_sum = 0;

		// Vytvorit transaction polozky
		foreach ($transaction_items as $transaction_item) {
					
			$transaction_item_data = $this->processTransactionItemData($transaction_item, 'NAKUP', $default_currency);

			if ( count($transaction_item_data) == 0 ){
				break;
			}

			$transaction_item_data['transaction_id'] = $this->id;

			TransactionItem::create($transaction_item_data);

			// Calculate transaction item price after saving it
			$transaction_item_price = floatval($transaction_item['fees']) + (floatval($transaction_item['quantity']) * floatval($transaction_item['price']));

			// Create account items
			$account_item = AccountItem::where('account_id', $this->endAccount->id)
			->where('name', $transaction_item_data['name'])
			->latest()->first();
			
			if( isset( $account_item ) ){
				$account_item->quantity = ( floatval($account_item->quantity) + floatval($transaction_item['quantity']) );
				$account_item->average_buy_price = ( floatval($account_item->average_buy_price) + floatval($transaction_item['price'])) / 2;
				$account_item->current_price = floatval($transaction_item['price']);

				$account_item->save();
			}else{
				$account_item = AccountItem::create(
					[
						'account_id' => $this->endAccount->id,
						'item_type_id' => $transaction_item['item_type_id'],
						'currency_id' => $transaction_item['currency_id'],
						'name' => $transaction_item_data['name'],
						'quantity' => $transaction_item['quantity'],
						'average_buy_price' => $transaction_item['price'],
						'current_price' => $transaction_item['price'],
					]
				);

				$account_item->save();
			}

			// Decrease cash
			$source_account = $this->endAccount;
			
			$this->decreaseCash($source_account, $this->currency->id, $transaction_item_price, 1);	

			$price_sum += $transaction_item_price;		
		}

		if( $price_sum > 0 ){
			$this->value = $price_sum;				
			$this->save();
		}
	}

	public function deleteTransactionPrijem(){
		$end_account = $this->endAccount;

		$this->decreaseCash($end_account, $this->currency->id, $this->value, 1);		

		$end_account->value = floatval($end_account->value) - (floatval($this->value));
		$end_account->save();
	}

	public function deleteTransactionVydaj(){
		$source_account = $this->sourceAccount;

		$this->increaseCash($source_account, $this->currency->id, $this->value, 1);		

		$source_account->value = floatval($source_account->value) + (floatval($this->value));
		$source_account->save();
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

				if( !isset($account_item) ){
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

				$this->decreaseCash($this->sourceAccount, $currency_id, $quantity, $price);	
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

				$this->increaseCash($this->sourceAccount, $currency_id, $quantity, $price);	
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
