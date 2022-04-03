<?php

namespace App\Http\Livewire\Modals;

use Closure;
use App\Models\Account;
use App\Models\Currency;
use App\Models\ItemType;

use App\Models\Transaction;
use App\Http\Livewire\Modal;
use App\Models\AccountItem;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;

class AccountModal extends Modal implements HasForms
{
	use InteractsWithForms;

	public $timestamps = false;

	public function mount(): void 
    {
        $this->form->fill();
    } 

	protected function getFormSchema(): array 
    {
        return [            
			TextInput::make('name')->required()->label('Nazov'),
			//TextInput::make('value')->required()->label('Hodnota'),
			TextInput::make('icon')->required()->label('Ikona'),
			Select::make('currency_id')->options(Currency::all()->pluck('name', 'id'))->label('Mena'),

			Repeater::make('account_items')
				->schema([
					Select::make('item_type_id')
						->options(ItemType::all()->pluck('name', 'id'))
						->required()->label('Typ polozky'),
					TextInput::make('name')->required()->label('Nazov'),
					TextInput::make('quantity')->numeric()->required()->label('Pocet'),
					TextInput::make('average_buy_price')->numeric()->required()->label('Priemerna nakupna cena'),
					TextInput::make('current_price')->numeric()->required()->label('Aktualna cena'),
					Select::make('currency_id')
						->options(Currency::all()->pluck('name', 'id'))
						->required()->label('Mena'),
				])
				->createItemButtonLabel('Pridat Akciu alebo Kryptomenu')
				->columns(3)
				->hidden(function (Closure $get) {
					//$transaction_type =  $get('transaction_type_id');					
					//return (!in_array($transaction_type, [3, 4, 6]));
			}),
			
			Repeater::make('finance_items')
			->schema([
				TextInput::make('quantity')->numeric()->required()->label('Mnozstvo'),				
				//TextInput::make('price')->numeric()->required()->label('Aktualna cena'),
				Select::make('currency_id')
					->options(Currency::all()->pluck('name', 'id'))
					->required()->label('Mena'),
			])
			->createItemButtonLabel('Pridat menu')
			->columns(3)
			->hidden(function (Closure $get) {
					//$transaction_type =  $get('transaction_type_id');					
					//return (!in_array($transaction_type, [3, 4, 6]));
			})
        ];
    } 

	protected function getFormModel(): string 
    {
        return Account::class;
    } 
 
    public function render(): View
    {
        return view('livewire.account-modal');
    }

	public function submit()
    {
        $this->validate();

		$user_id = (auth()->user() ? auth()->user()->id : 4);
 
        // Execution doesn't reach here if validation fails. 
        $account = Account::create([
            'name' => $this->name,
            'user_id' => $user_id,
			'icon' => $this->icon,
			'currency_id' => $this->currency_id,
			'value' => 0
        ]);

		$price_sum = 0;

		if( isset($account) && isset($this->finance_items) ){	

			$price_sum = 0;

			foreach ($this->finance_items as $finance_item) {
				$finance_item_data = [
					'name' => 'Peniaze',
					'item_type_id' => 3,
					'quantity' => $finance_item['quantity'],
					'currency_id' => $finance_item['currency_id'],
					'average_buy_price' => 1,
					'current_price' => 1,
				];

				$price_sum += floatval($finance_item['quantity']);

				foreach ($finance_item_data as $key => $value) {
					if( empty($value) ){
						unset($account_item_data[$key]);
					}
				}

				if ( count($finance_item_data) == 0 ){
					break;
				}

				$finance_item_data['account_id'] = $account->id;

				AccountItem::create($finance_item_data);
			}
		}

		if( isset($account) && isset($this->account_items) ){

			foreach ($this->account_items as $account_item) {
				$account_item_data = [
					'name' => $account_item['name'],
					'item_type_id' => $account_item['item_type_id'],
					'quantity' => $account_item['quantity'],
					'currency_id' => $account_item['currency_id'],
					'average_buy_price' => $account_item['average_buy_price'],
					'current_price' => $account_item['current_price'],
				];

				$price_sum += floatval($account_item['current_price']) * floatval($account_item['quantity']);

				foreach ($account_item_data as $key => $value) {
					if( empty($value) ){
						unset($account_item_data[$key]);
					}
				}

				if ( count($account_item_data) == 0 ){
					break;
				}

				$account_item_data['account_id'] = $account->id;

				AccountItem::create($account_item_data);
				
			}
			
		}

		if( $price_sum > 0 ){
			$account->value = $price_sum;				
			$account->save();
		}

		session()->flash('message', 'Ucet bol uspesne vytvoreny.');

		$this->dispatchBrowserEvent('accountStore',
		[
            'type' => 'success',
            'message' => 'Ucet bol uspesne vytvoreny'
        ]);

		$this->emit('refreshParent');
    }

}