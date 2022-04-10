<?php

namespace App\Http\Livewire\Modals;


use Closure;
use Livewire\Component as Livewire;
use App\Models\Account;
use App\Models\Category;

use App\Models\Currency;
use App\Models\ItemType;
use App\Models\AccountItem;
use App\Models\AddressBook;
use App\Models\Transaction;
use App\Http\Livewire\Modal;
use App\Models\TransactionItem;
use App\Models\TransactionType;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\Component;

class TransactionModal extends Modal implements HasForms
{
	use InteractsWithForms;

	public ?Transaction $transaction = null;

	public $transaction_type_id = '';
	public $name = '';
	public $value = '';
	public $transaction_time = '';
	public $currency_id = '';
	public $category_id = '';
	public $address_book_id = '';
	public $source_account_id = '';
	public $end_account_id = '';
	public $transaction_items = [];
	public $transaction_sell_items = [];

	public $showMessage = false;

	public function mount(): void 
    {
        $this->form->fill();
    } 

	public function delete($id){
		$this->transaction = Transaction::find($id);

		$transaction_type = $this->transaction->transactionType;

		$this->transaction->deleteTransaction($transaction_type->code);

		$this->reset();

		$this->emit('refreshParent');

		$this->emit('showMessage');

		session()->flash('message', 'Transakcia bola uspesne vymazana.');	
	}

	public function edit($id){
		$this->transaction = Transaction::find($id);

		$transaction_items = TransactionItem::where('transaction_id', $id)->get();

		$transaction_items_form = [];

		foreach ($transaction_items as $transaction_item) {
			$transaction_items_form[] = [
				'item_type_id' => $transaction_item->item_type_id,
				'name' => $transaction_item->name,
				'quantity' => $transaction_item->quantity,
				'price' => $transaction_item->price,
				'currency_id' => $transaction_item->currency_id,
				'fees' => ($transaction_item->fees ?? 0),
				'fees_currency_id' => $transaction_item->fees_currency_id,
			];
		}

		$this->form->fill(
			[
				'transaction_type_id' => $this->transaction->transaction_type_id,
				'name' => $this->transaction->name,
				'value' => $this->transaction->value,
				'transaction_time' => $this->transaction->transaction_time,
				'currency_id' => $this->transaction->currency_id,
				'category_id' => $this->transaction->category_id,
				'address_book_id' => $this->transaction->address_book_id,
				'source_account_id' => $this->transaction->source_account_id,
				'end_account_id' => $this->transaction->source_account_id,
				'transaction_items' => $transaction_items_form
			]
		);
		$this->show = true;
	}

	protected function getFormSchema(): array 
    {
        return [
			Select::make('transaction_type_id')
				->options(TransactionType::all()->pluck('name', 'id'))->label('Typ transakcie')->required()->reactive(),
			TextInput::make('name')
				->label('Nazov')->nullable(),
			TextInput::make('value')
				->hidden(function (Closure $get) {
						$transaction_type =  $get('transaction_type_id');					
						return (!in_array($transaction_type, [1, 2]));
					}
				)->label('Suma')->required()->gt(0),
			DateTimePicker::make('transaction_time')->nullable(),
			Select::make('currency_id')
				->options(Currency::all()->pluck('name', 'id'))				
				->default(function(){
					$default_currency = (auth()->user() ? auth()->user()->currency_id : 1);
					return $default_currency;
				})->label('Mena')->nullable(),
			Select::make('category_id')
				->options(Category::all()->pluck('name', 'id'))
				->label('Kategoria')->nullable(),
			Select::make('address_book_id')
				->options(AddressBook::all()->pluck('name', 'id'))
				->hidden(function (Closure $get) {
						$transaction_type =  $get('transaction_type_id');					
						return (!in_array($transaction_type, [1, 2, 5]));
					}
				)->label('Adresar')->nullable(),
			Select::make('source_account_id')
				->options(Account::all()->pluck('name', 'id'))				
				->hidden(function (Closure $get) {
						$transaction_type =  $get('transaction_type_id');					
						return (!in_array($transaction_type, [2, 3, 4, 5, 6]));
					}
				)->label('Zdrojovy ucet')->reactive()->required(),
			Select::make('end_account_id')
				->options(Account::all()
				->pluck('name', 'id'))
				->label('Cielovy ucet')
				->hidden(function (Closure $get) {
						$transaction_type =  $get('transaction_type_id');					
						return (!in_array($transaction_type, [1, 6]));
					}
				)->required(),
			Repeater::make('transaction_items')
				->schema([
					Select::make('item_type_id')
						->options(ItemType::all()->pluck('name', 'id'))
						->required()->label('Typ polozky'),
					TextInput::make('name')->required()->label('Nazov'),
					TextInput::make('quantity')->numeric()->required()->label('Pocet')->gt(0),
					TextInput::make('price')->numeric()->required()->label('Cena')->gt(0),
					Select::make('currency_id')
						->options(Currency::all()->pluck('name', 'id'))
						->nullable()->label('Mena')->default(function(){
							$default_currency = (auth()->user() ? auth()->user()->currency_id : 1);
							return $default_currency;
						}),
					TextInput::make('fees')->numeric()->nullable()->default(0),
					Select::make('fees_currency_id')
						->options(Currency::all()->pluck('name', 'id'))
						->nullable()->label('Mena poplatku')->default(function(){
							$default_currency = (auth()->user() ? auth()->user()->currency_id : 1);
							return $default_currency;
						}),
				])
				->createItemButtonLabel('Pridat polozku')
				->defaultItems(0)
				->columns(3)
				->hidden(function (Closure $get) {
					$transaction_type =  $get('transaction_type_id');					
					return !(in_array($transaction_type, [3, 6]) || $this->transaction);
				}),
			Repeater::make('transaction_sell_items')
				->schema([
					Select::make('item_type_id')
						->options(ItemType::all()->pluck('name', 'id'))
						->required()->label('Typ polozky')->reactive(),
					Select::make('transaction_item_name')->options(
						function (Livewire $livewire, Closure $get, Component $component) {
							$source_account_id = $livewire->source_account_id;
							$item_type_id = $get('item_type_id');

							$account_items = AccountItem::where('account_id', $source_account_id)
							->when($item_type_id, function($query, $item_type_id) {
								return $query->where('item_type_id', $item_type_id);
							})
							->orderBy('name', 'asc')->get();

							$options = [];

							foreach ($account_items as $account_item) {
								$account_item_name = $account_item->name;

								$quantity = DB::table('account_items')
								->where('account_id', $source_account_id)
								->when($item_type_id, function($query, $item_type_id) {
									return $query->where('item_type_id', $item_type_id);
								})
								->when($account_item_name, function($query, $account_item_name){
									return $query->where('name', $account_item_name);
								})
								->sum('quantity');

								if( isset($this->transaction) ){
									$transaction_items = $this->transaction->transactionItems();

									foreach ($transaction_items as $transaction_item) {
										if($account_item->name == $transaction_item->name){
											$quantity = $transaction_item->quantity;
										}
									}
								}

								$options[$account_item->name] = $account_item->name . " - " . $quantity;
							}

							return $options;
						}
					)->label('Nazov')->required()->reactive(),
					TextInput::make('quantity')->numeric()->required()->label('Pocet')
						->default(function (Livewire $livewire, Closure $get){
							$name = $get('transaction_item_name');
							$source_account_id = $livewire->source_account_id;
							
							$quantity = DB::table('account_items')
							->where('account_id', $source_account_id)
							->where('name', $name)
							->sum('quantity');

							return $quantity;
						}
					)->gt(0)->reactive(),
					TextInput::make('price')->numeric()->required()->label('Cena')->gt(0),
					Select::make('currency_id')
						->options(Currency::all()->pluck('name', 'id'))
						->nullable()->label('Mena')->default(function(){
							$default_currency = (auth()->user() ? auth()->user()->currency_id : 1);
							return $default_currency;
						}),
					TextInput::make('fees')->numeric()->nullable()->default(0),
					Select::make('fees_currency_id')
						->options(Currency::all()->pluck('name', 'id'))
						->nullable()->label('Mena poplatku')->default(function(){
							$default_currency = (auth()->user() ? auth()->user()->currency_id : 1);
							return $default_currency;
						}),
				])
				->defaultItems(0)
				->createItemButtonLabel('Pridat polozku')
				->columns(3)
				->hidden(function (Closure $get) {
					$transaction_type =  $get('transaction_type_id');					
					return !(in_array($transaction_type, [4]) || $this->transaction);
				}),
        ];
    } 

	protected function getFormModel(): string 
    {
        return Transaction::class;
    } 
 
    public function render(): View
    {
        return view('livewire.transaction-modal');
    }

	public function submit()
    {
        $this->validate();

		$user_id = (auth()->user() ? auth()->user()->id : 4);
		$default_currency = (auth()->user() ? auth()->user()->currency_id : 1);

		$transaction_data = [
            'name' => $this->name,
            'user_id' => $user_id,
			'category_id' => $this->category_id,
			'transaction_type_id' => $this->transaction_type_id,
			'currency_id' => $this->currency_id,
			'address_book_id' => $this->address_book_id,
			'source_account_id' => $this->source_account_id,
			'end_account_id' => $this->end_account_id,
			'transaction_time' => $this->transaction_time,
			'name' => $this->name,			
        ];

		foreach ($transaction_data as $key => $value) {
			if( empty($value) ){
				unset($transaction_data[$key]);
			}
		}

		// Ked nie je nastavene meno tak nastavit ako typ transakcie
		if( !isset($transaction_data['name']) ){
			$transaction_type = TransactionType::find($transaction_data['transaction_type_id']);
			$transaction_data['name'] = $transaction_type->name;
		}


		// Defaultne currency
		if( !isset($transaction_data['currency_id']) ){
			$transaction_data['currency_id'] = $default_currency;
		}

		$transaction_data['value'] = $this->value ? $this->value : 0;

		if( is_null($this->end_account_id) && !is_null($transaction_data['source_account_id']) ){
			$transaction_data['end_account_id'] = $transaction_data['source_account_id'];
		}

		if( is_null($this->source_account_id) && !is_null($transaction_data['end_account_id']) ){
			$transaction_data['source_account_id'] = $transaction_data['end_account_id'];
		}
 
        // Execution doesn't reach here if validation fails. 
        $transaction = Transaction::create($transaction_data);

		// Prijem
		if( in_array(intval($this->transaction_type_id), [1]) ){
			$finance_item = AccountItem::where('account_id', $transaction->end_account_id)
			->where('item_type_id', 3)
			->where('currency_id', intval($transaction_data['currency_id']))
			->latest()->first();

			if ( !isset($finance_item) ){
				$finance_item = AccountItem::firstOrNew(
					[
						'account_id' => $transaction->end_account_id,
						'item_type_id' => 3,
						'currency_id' => intval($transaction_data['currency_id'])
					],
					[
						'name' => 'Peniaze',
						'item_type_id' => 3,
						'quantity' => floatval($transaction_data['value']),
						'average_buy_price' => 1,
						'current_price' => 1,
					]
				);

				$finance_item->save();
			}else{
				$finance_item->quantity = (floatval($finance_item->quantity) + floatval($transaction_data['value']));

				$finance_item->save();
			}

			$account = Account::where('id', $transaction->end_account_id)->latest()->first();;
			$account->value = (floatval($account->value) + floatval($transaction_data['value']));
			$account->save();
		}

		// Vydaj
		if( in_array(intval($this->transaction_type_id), [2]) ){

			$finance_items = AccountItem::where('account_id', $transaction->end_account_id)
			->where('item_type_id', 3)
			->get();			

			foreach ($finance_items as $finance_item) {
				if($finance_item->currency_id == $transaction_data['currency_id']){
					$finance_item->quantity = (floatval($finance_item->quantity) - floatval($transaction_data['value']));
					$finance_item->save();
				}
			}

			$account = Account::where('id', $transaction->end_account_id)->latest()->first();
			$account->value = (floatval($account->value) - floatval($transaction_data['value']));
			$account->save();
		}

		// Nakup a predaj akcii/kryptomien
		if( in_array(intval($this->transaction_type_id), [3, 4]) ){
			if( isset($transaction) && isset($this->transaction_items) ){	

				$transaction_items = ($transaction_data['transaction_type_id'] == 4 ?  $this->transaction_sell_items : $this->transaction_items );
			
				$price_sum = 0;

				// Create transaction
				foreach ($transaction_items as $transaction_item) {
					$transaction_item_data = [
						'name' => ($this->transaction_type_id == 4 ? $transaction_item['transaction_item_name'] : $transaction_item['name']),
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
	
					if ( count($transaction_item_data) == 0 ){
						break;
					}
	
					$transaction_item_data['transaction_id'] = $transaction->id;
	
					TransactionItem::create($transaction_item_data);
	
					$transaction_price = floatval($transaction_item['fees']) + (floatval($transaction_item['quantity']) * floatval($transaction_item['price']));
	
					$account_item = AccountItem::where('account_id', $transaction->end_account_id)
					->where('name', $transaction_item_data['name'])
					->firstOr(function () {
						return null;
					});

					if( $account_item ){
						$account_item->quantity = ($transaction_data['transaction_type_id'] == 4 ? ( $account_item->quantity - floatval($transaction_item['quantity']) ) : ( $account_item->quantity + floatval($transaction_item['quantity']) ));
						$account_item->average_buy_price = ($account_item->average_buy_price + floatval($transaction_item['price'])) / 2;
						$account_item->current_price = floatval($transaction_item['price']);

						$account_item->save();
					} else {
						$account_item = AccountItem::create(
							[
								'account_id' => $transaction->end_account_id,
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

					// Buy
					if( $this->transaction_type_id == 3){
						$finance_items = AccountItem::where('account_id', $transaction->end_account_id)
						->where('item_type_id', 3)
						->get();

						foreach ($finance_items as $finance_item) {
							if($finance_item->currency_id == $transaction_item['currency_id']){
								$finance_item->quantity = (floatval($finance_item->quantity) - $transaction_price);

								$finance_item->save();
							}
						}
					}

					// Sell
					if( $this->transaction_type_id == 4){

						$finance_item = AccountItem::where('account_id', $transaction->end_account_id)
						->where('item_type_id', 3)
						->where('currency_id', intval($transaction_item['currency_id']))
						->latest()->first();

						if ( !isset($finance_item) ){
							$finance_item = AccountItem::firstOrNew(
								[
									'account_id' => $transaction->end_account_id,
									'item_type_id' => 3,
									'currency_id' => intval($transaction_item['currency_id'])
								],
								[
									'name' => 'Peniaze',
									'item_type_id' => 3,
									'quantity' => $transaction_price,
									'average_buy_price' => 1,
									'current_price' => 1,
								]
							);
	
							$finance_item->save();
						}else{
							$finance_item->quantity = (floatval($finance_item->quantity) + floatval($transaction_data['value']));

							$finance_item->save();
						}
						
					}

					$price_sum += $transaction_price;	
				}
	
				if( $price_sum > 0 ){
					$transaction->value = $price_sum;				
					$transaction->save();
				}
				
			}
		}		

		$this->reset();

		$this->dispatchBrowserEvent('transactionStore',
		[
            'type' => 'success',
            'message' => 'Transakcia bola uspesne vytvorena'
        ]);

		$this->emit('refreshParent');

		$this->emit('showMessage');

		session()->flash('message', 'Transakcia bola uspesne vytvorena.');	
    }

}