<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Transaction extends Model
{
    use HasFactory;

	private $user_id;

	private $currency_rates = [
		'EUR' => [
			'USD' => 1.09,
		],
		'USD' => [
			'EUR' => 0.92,
		]
	];

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

	public function file()
    {
        return $this->hasOne(File::class, 'transaction_id', 'id');
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

	protected function transactionTime(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
				try {
					$user_date_type = (auth()->user() ? auth()->user()->date_type : 'Y-m-d H:i:s');
					$formated_date_time = Carbon::parse($value)->format($user_date_type);
					return $formated_date_time;
				} catch (\Throwable $th) {
					$formated_date_time = Carbon::parse($value)->format('Y-m-d H:i:s');
					return $formated_date_time;
				}
			},
			set: function($value){
				$formated_date_time = Carbon::parse($value);
				return $formated_date_time;
			}
        );
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
			$this->decreaseCategoryBudget();
			$this->delete();
		}
		else if($type == 'DLZOBA'){
			$this->deleteTransactionDlzoba();
		}
		else if($type == 'TRANSFER'){
			$this->deleteTransactionTransfer();
		}
	}

	public function getAccountDefaultCurrency($account){
		if( isset($account) ){
			return $account->currency->id;
		}

		return null;
	}

	public function createTransactionItems($type, $default_currency, $transaction_sell_items, $transaction_items, $dept_paid){
		
		if($type === 'NAKUP'){
			/*$account_default_currency = $this->getAccountDefaultCurrency($this->endAccount);
			$default_currency = ( is_null($account_default_currency) ? $default_currency : $account_default_currency );*/
			
			$this->createAccountItemsNakup($transaction_items, $default_currency);
		}
		else if ($type === 'PREDAJ'){
			/*$account_default_currency = $this->getAccountDefaultCurrency($this->endAccount);
			$default_currency = ( is_null($account_default_currency) ? $default_currency : $account_default_currency );*/

			$this->createAccountItemsPredaj($transaction_sell_items, $default_currency);
		}
		else if($type === 'PRIJEM'){
			/*$account_default_currency = $this->getAccountDefaultCurrency($this->endAccount);
			$default_currency = ( is_null($account_default_currency) ? $default_currency : $account_default_currency );*/

			$this->createAccountItemsPrijem();
		}
		else if($type === 'VYDAJ'){
			/*$account_default_currency = $this->getAccountDefaultCurrency($this->endAccount);
			$default_currency = ( is_null($account_default_currency) ? $default_currency : $account_default_currency );*/
			
			$this->createAccountItemsVydaj();
		}
		else if($type === 'TRANSFER'){
			$this->createTransactionTransfer();
		}
		else if($type === 'DLZOBA'){
			if( $dept_paid && !$this->paid )
			{
				$this->createTransactionDlzoba();
			}
		}
		
	}

	public function increaseCategoryBudget(){
		if(!isset($this->category->id) ){
			return;
		}

		$budgets = Budget::where('user_id', $this->user_id)
		->where('category_id', $this->category->id)
		->whereDate('start_time', '<=', Carbon::parse($this->getRawOriginal('transaction_time')))
		->get();

		foreach ($budgets as $budget) {
			$start_time = Carbon::parse($budget->start_time);

			if( $budget->budget_period === 'deň' )
			{
				$start_time = $start_time->addDay();
			}
			else if( $budget->budget_period === 'týždeň' )
			{
				$start_time = $start_time->addWeek();
			}
			else if( $budget->budget_period === 'mesiac' )
			{
				$start_time = $start_time->addMonth();
			}
			else if( $budget->budget_period === 'rok' )
			{
				$start_time = $start_time->addYear();
			}

			$transaction_time = Carbon::parse($this->getRawOriginal('transaction_time'));

			if($start_time->gte($transaction_time)){
				$budget->reached = $budget->reached + $this->value;
				$budget->save();
			}
		}
	}

	public function decreaseCategoryBudget(){
		if(!isset($this->category->id) ){
			return;
		}

		$budgets = Budget::where('user_id', $this->user_id)
		->where('category_id', $this->category->id)
		->whereDate('start_time', '<=', Carbon::parse($this->getRawOriginal('transaction_time')))
		->get();

		foreach ($budgets as $budget) {
			$start_time = Carbon::parse($budget->start_time);

			if( $budget->budget_period === 'deň' )
			{
				$start_time = $start_time->addDay();
			}
			else if( $budget->budget_period === 'týždeň' )
			{
				$start_time = $start_time->addWeek();
			}
			else if( $budget->budget_period === 'mesiac' )
			{
				$start_time = $start_time->addMonth();
			}
			else if( $budget->budget_period === 'rok' )
			{
				$start_time = $start_time->addYear();
			}

			$transaction_time = Carbon::parse($this->getRawOriginal('transaction_time'));

			if($start_time->gte($transaction_time)){
				$budget->reached = $budget->reached - $this->value;
				$budget->save();
			}
		}
		
	}

	/**
	 * Increase overal account value
	 * 
	 * @param int $account_id Account id of account from which we want to subtract value
	 * @param int $amout Amout of money we are decreasing
	 * @return void Saves new value
	 */
	public function increaseAccountValue($account_id){
		$transaction_currency_name = $this->currency->name;

		$account = Account::where('id', $account_id)->latest()->first();
		
		$transaction_value_converted = $this->convertCurrency($this->value, $transaction_currency_name, $account->currency->name);
		
		$account->value = (floatval($account->value) + floatval($transaction_value_converted));
		$account->save();
	}

	public function decreaseAccountValue($account_id){
		$transaction_currency_name = $this->currency->name;

		$account = Account::where('id', $account_id)->latest()->first();
		
		$transaction_value_converted = $this->convertCurrency($this->value, $transaction_currency_name, $account->currency->name);
		
		$account->value = (floatval($account->value) - floatval($transaction_value_converted));
		$account->save();
	}

	public function increaseCash($account, $quantity, $price){
		$total_cash = floatval($quantity) * floatval($price);
		$transaction_currency_id = $this->currency->id;

		$finance_item = AccountItem::where('account_id', $account->id)
		->where('item_type_id', 3)
		->where('currency_id', intval($transaction_currency_id))
		->latest()->first();

		if( !isset($finance_item) ){

			$finance_item = AccountItem::firstOrNew(
				[
					'account_id' => $account->id,
					'item_type_id' => 3,
					'currency_id' => intval($transaction_currency_id)
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

	public function decreaseCash($account, $quantity, $price){
		$total_cash = floatval($quantity) * floatval($price);
		$transaction_currency_id = $this->currency->id;

		$finance_item = AccountItem::where('account_id', $account->id)
		->where('item_type_id', 3)
		->where('currency_id', intval($transaction_currency_id))
		->latest()->first();

		if( !isset($finance_item) ){

			$finance_item = AccountItem::firstOrNew(
				[
					'account_id' => $account->id,
					'item_type_id' => 3,
					'currency_id' => intval($transaction_currency_id)
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

	public function deleteTransactionDlzoba(){
		$this->increaseCash($this->sourceAccount, $this->value, 1);
		$this->increaseAccountValue($this->sourceAccount->id);

		$this->delete();
	}

	public function createTransactionDlzoba(){
		$this->decreaseCash($this->sourceAccount, $this->value, 1);
		$this->decreaseAccountValue($this->sourceAccount->id);

		$this->paid = true;
		$this->save();
	}

	public function createTransactionTransfer(){
		$this->decreaseCash($this->sourceAccount, $this->currency->id, $this->value, 1);	
		$this->increaseCash($this->endAccount, $this->currency->id, $this->value, 1);	

		$this->decreaseAccountValue($this->sourceAccount->id);
		$this->increaseAccountValue($this->endAccount->id);
	}

	public function deleteTransactionTransfer(){
		$this->increaseCash($this->sourceAccount, $this->value, 1);	
		$this->decreaseCash($this->endAccount, $this->value, 1);
		
		$this->increaseAccountValue($this->sourceAccount->id);
		$this->decreaseAccountValue($this->endAccount->id);

		$this->delete();
	}

	public function deleteTransactionItems(){
		$transaction_items = $this->transactionItems();

		if( $transaction_items ){
			$transaction_items->delete();
		}
	}

	public function createAccountItemsVydaj(){
		$finance_item = AccountItem::where('account_id', $this->sourceAccount->id)
		->where('item_type_id', 3)
		->where('currency_id', $this->currency->id)
		->latest()->first();				

		if( isset($finance_item) ){
			$finance_item->quantity = (floatval($finance_item->quantity) - floatval($this->value));
			$finance_item->save();

			$this->decreaseAccountValue($this->sourceAccount->id);
		}

		$this->increaseCategoryBudget();
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

		$this->increaseAccountValue($this->endAccount->id);
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

	public function convertCurrency($amout, $from, $to){
		if( $from != $to ){
			if( isset($this->currency_rates[$to][$from]) ){
				$amout = $this->currency_rates[$to][$from] * floatval($amout);
			}
		}

		return $amout;
	}
	public function createAccountItemsPredaj($transaction_items, $default_currency){
		$price_sum = 0;

		foreach ($transaction_items as $transaction_item) {
			$transaction_item_data = $this->processTransactionItemData($transaction_item, 'PREDAJ', $default_currency);
			
			if ( count($transaction_item_data) == 0 ){
				break;
			}

			$transaction_item_data['transaction_id'] = $this->id;

			$transaction_item_obj = TransactionItem::create($transaction_item_data);

			// Convert fee to transaction currency
			$fees_currency = $transaction_item_obj->feesCurrency;
			$currency = $transaction_item_obj->currency;

			$transaction_fee = $this->convertCurrency($transaction_item['fees'], $fees_currency->name, $currency->name);

			$transaction_item_price = floatval($transaction_fee) + (floatval($transaction_item['quantity']) * floatval($transaction_item['price']));
	
			$account_item = AccountItem::where('account_id', $this->sourceAccount->id)
			->where('name', $transaction_item_data['name'])
			->where('currency_id', $currency->id)
			->firstOr(function () {
				return null;
			});

			/*$account_currency = $account_item->account->currency->name;
			$account_item_currency = $account_item->currency->name;

			$transaction_item_converted_price = $this->convertCurrency($transaction_item['price'], $from, $account_currency);*/

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
			
			$this->increaseCash($source_account, $transaction_item_price, 1);		

			$price_sum += $this->convertCurrency($transaction_item_price, $currency->name, $account_item->account->currency->name);
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

			$transaction_item_obj = TransactionItem::create($transaction_item_data);

			// Convert fee to transaction currency
			$fees_currency = $transaction_item_obj->feesCurrency;
			$currency = $transaction_item_obj->currency;

			$transaction_fee = $this->convertCurrency($transaction_item['fees'], $fees_currency->name, $currency->name);

			// Calculate transaction item price after saving it
			$transaction_item_price = floatval($transaction_fee) + (floatval($transaction_item['quantity']) * floatval($transaction_item['price']));

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

			$converted_transaction_price = $this->convertCurrency($transaction_item_price, $currency->name, $account_item->account->currency->name);
			
			$this->decreaseCash($source_account, $transaction_item_price, 1);	

			$price_sum += $converted_transaction_price;
		}

		if( $price_sum > 0 ){
			$this->value = $price_sum;				
			$this->save();
		}
	}

	public function deleteTransactionPrijem(){
		$end_account = $this->endAccount;

		$this->decreaseCash($end_account, $this->currency->id, $this->value, 1);		

		$converted_price = $this->convertCurrency($this->value, $end_account->currency->name, $this->currency->name);

		$end_account->value = floatval($end_account->value) - $converted_price;
		$end_account->save();
	}

	public function deleteTransactionVydaj(){
		$source_account = $this->sourceAccount;

		$this->increaseCash($source_account, $this->value, 1);		

		$converted_price = $this->convertCurrency($this->value, $source_account->currency->name, $this->currency->name);

		$source_account->value = floatval($source_account->value) + $converted_price;
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
				->where('account_id', $this->sourceAccount->id)
				->where('currency_id', intval($currency_id))
				->latest()->first();

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
				}
				else if ($quantity < $account_item->quantity)
				{
					$latest_transaction = $this->getLatestPrice($id);

					$account_item->average_buy_price = (floatval($account_item->average_buy_price) + floatval($price)) / 2;
					$account_item->quantity = (floatval($account_item->quantity) + floatval($quantity));
					$account_item->current_price = $latest_transaction->price;
					
					$account_item->save();	
				}

				$converted_price = $this->convertCurrency($price, $source_account->currency->name, $account_item->account->currency->name);

				$source_account->value = floatval($source_account->value) + ($converted_price * floatval($quantity));
				$source_account->save();

				$this->decreaseCash($this->sourceAccount, $quantity, $price);	
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
				->where('account_id', $this->sourceAccount->id)
				->where('currency_id', intval($currency_id))
				->latest()->first();

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

					$converted_price = $this->convertCurrency($price, $source_account->currency->name, $account_item->account->currency->name);

					$source_account->value = floatval($source_account->value) - ($converted_price * floatval($quantity));
					$source_account->save();
				}

				$this->increaseCash($this->sourceAccount, $quantity, $price);	
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
