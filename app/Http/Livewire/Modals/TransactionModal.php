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

class TransactionModal extends Modal implements HasForms
{
	use InteractsWithForms;

	public function mount(): void 
    {
        $this->form->fill();
    } 

	protected function getFormSchema(): array 
    {
		//dd(BelongsToSelect::make('transaction_type_id'));
        return [            
			Select::make('transaction_type_id')->options(TransactionType::all()->pluck('name', 'id'))->label('Typ transakcie')->required()->reactive(),
			TextInput::make('name')->required()->label('Nazov'),
			TextInput::make('value')->required()->label('Suma')
				->hidden(function (Closure $get) {
					$transaction_type =  $get('transaction_type_id');					
					return (!in_array($transaction_type, [1, 2]));
				}
			),
			DateTimePicker::make('transaction_time'),
			Select::make('currency_id')->options(Currency::all()->pluck('name', 'id'))->label('Mena'),
			Select::make('category_id')->options(Category::all()->pluck('name', 'id'))->label('Kategoria'),
			Select::make('address_book_id')->options(AddressBook::all()->pluck('name', 'id'))->label('Adresar')
				->hidden(function (Closure $get) {
					$transaction_type =  $get('transaction_type_id');					
					return (!in_array($transaction_type, [1, 2, 5]));
				}
			),
			Select::make('source_account_id')
				->options(Account::all()->pluck('name', 'id'))
				->label('Zdrojovy ucet')
				->hidden(function (Closure $get) {
					$transaction_type =  $get('transaction_type_id');					
					return (!in_array($transaction_type, [2, 3, 4, 5, 6]));
				}
			)->reactive(),
			Select::make('end_account_id')
				->options(Account::all()
				->pluck('name', 'id'))
				->label('Cielovy ucet')
				->hidden(function (Closure $get) {
					$transaction_type =  $get('transaction_type_id');					
					return (!in_array($transaction_type, [6]));
				}
			),
			Repeater::make('transaction_items')
				->schema([
					Select::make('item_type_id')
						->options(ItemType::all()->pluck('name', 'id'))
						->required()->label('Typ polozky'),
					TextInput::make('name')->required()->label('Nazov'),
					TextInput::make('quantity')->numeric()->required()->label('Pocet'),
					TextInput::make('price')->numeric()->required()->label('Cena'),
					Select::make('currency_id')
						->options(Currency::all()->pluck('name', 'id'))
						->required()->label('Mena'),
					TextInput::make('fees')->numeric()->required(),
					Select::make('fees_currency_id')
						->options(Currency::all()->pluck('name', 'id'))
						->required()->label('Mena poplatku'),
				])
				->createItemButtonLabel('Pridat polozku')
				->columns(3)
				->hidden(function (Closure $get) {
					$transaction_type =  $get('transaction_type_id');					
					return (!in_array($transaction_type, [3, 4, 6]));
				}),
			/*Repeater::make('transaction_sell_items')
				->schema([
					Select::make('item_type_id')
						->options(ItemType::all()->pluck('name', 'id'))
						->required()->label('Typ polozky'),
					Select::make('transaction_item_name')->options(
						function (Livewire $livewire, Closure $get) {
							$source_account_id = $get('source_account_id');
							
							$options = AccountItem::where('account_id', $source_account_id)->orderBy('name', 'asc')->get()->pluck('name', 'id');

							return $options;
						}
					)->label('Nazov')->reactive(),
					TextInput::make('quantity')->numeric()->required()->label('Pocet')
					->default(function (Closure $get){
						$name = $get('transaction_item_name');
						$source_account_id = intval($get('source_account_id'));

						//dd($name, $source_account_id);
						
						$quantity = DB::table('account_items')
						->where('account_id', $source_account_id)
						->where('name', $name)
						->sum('quantity');

						return $quantity;
					}),
					TextInput::make('price')->numeric()->required()->label('Cena'),
					Select::make('currency_id')
						->options(Currency::all()->pluck('name', 'id'))
						->required()->label('Mena'),
					TextInput::make('fees')->numeric()->required(),
					Select::make('fees_currency_id')
						->options(Currency::all()->pluck('name', 'id'))
						->required()->label('Mena poplatku'),
				])
				->createItemButtonLabel('Pridat polozku')
				->columns(3)
				->hidden(function (Closure $get) {
					$transaction_type =  $get('transaction_type_id');					
					return (!in_array($transaction_type, [3, 4, 6]));
				}),*/
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

		$transaction_data['value'] = $this->value ? $this->value : 0;

		if(is_null($this->end_account_id)){
			$transaction_data['end_account_id'] = $transaction_data['source_account_id'];
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
			
				$price_sum = 0;
	
				foreach ($this->transaction_items as $transaction_item) {
					$transaction_item_data = [
						'name' => $transaction_item['name'],
						'item_type_id' => $transaction_item['item_type_id'],
						'quantity' => $transaction_item['quantity'],
						'price' => $transaction_item['price'],
						'currency_id' => $transaction_item['currency_id'],
						'fees' => $transaction_item['fees'],
						'fees_currency_id' => $transaction_item['fees_currency_id'],
					];
	
					foreach ($transaction_item_data as $key => $value) {
						if( empty($value) ){
							unset($transaction_item_data[$key]);
						}
					}
	
					if ( count($transaction_item_data) == 0 ){
						break;
					}
	
					$transaction_item_data['transaction_id'] = $transaction->id;
	
					TransactionItem::create($transaction_item_data);
	
					$transaction_price = floatval($transaction_item['fees']) + (floatval($transaction_item['quantity']) * floatval($transaction_item['price']));
	
					$account_item = AccountItem::where('account_id', $transaction->end_account_id)
					->where('name', $transaction_item['name'])
					->firstOr(function () {
						return null;
					});

					if( $account_item ){
						$account_item->quantity = ($transaction_data['transaction_type_id'] == 4 ? ( $account_item->quantity - floatval($transaction_item['quantity']) ) : ( $account_item->quantity + floatval($transaction_item['quantity']) ));
						$account_item->average_buy_price = ($account_item->average_buy_price + floatval($transaction_item['price'])) / 2;
						$account_item->current_price = floatval($transaction_item['price']);
					} else {
						$account_item = AccountItem::create(
							[
								'account_id' => $transaction->end_account_id,
								'item_type_id' => $transaction_item['item_type_id'],
								'currency_id' => $transaction_item['currency_id'],
								'name' => $transaction_item['name'],
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

		session()->flash('message', 'Transakcia bola uspesne vytvorena.');

		$this->dispatchBrowserEvent('transactionStore',
		[
            'type' => 'success',
            'message' => 'Transakcia bola uspesne vytvorena'
        ]);
    }

}