<?php

namespace App\Http\Livewire\Modals;


use Closure;
use App\Models\Account;
use App\Models\Currency;
use App\Models\AddressBook;

use App\Models\Transaction;
use App\Http\Livewire\Modal;
use App\Models\Category;
use App\Models\TransactionType;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TimePicker;

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
			TextInput::make('value')->required()->label('Hodnota'),
			DateTimePicker::make('transaction_time'),
			Select::make('currency_id')->options(Currency::all()->pluck('name', 'id'))->label('Mena'),
			Select::make('category_id')->options(Category::all()->pluck('name', 'id'))->label('Kategoria'),
			Select::make('address_book_id')->options(AddressBook::all()->pluck('name', 'id'))->label('Adresar'),
			Select::make('source_account_id')
				->options(Account::all()->pluck('name', 'id'))
				->label('Zdrojovy ucet')
				->hidden(function (Closure $get) {
					$transaction_type =  $get('transaction_type_id');					
					return (!in_array($transaction_type, [2, 3, 4, 5, 6]));
				}),
			Select::make('end_account_id')
				->options(Account::all()
				->pluck('name', 'id'))
				->label('Cielovy ucet')
				->hidden(function (Closure $get) {
					$transaction_type =  $get('transaction_type_id');					
					return (!in_array($transaction_type, [1, 3, 6]));
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
 
        // Execution doesn't reach here if validation fails. 
        Transaction::create([
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
			'value' => $this->value
        ]);

		session()->flash('message', 'Transakcia bola uspesne vytvorena.');

		$this->dispatchBrowserEvent('transactionStore',
		[
            'type' => 'success',
            'message' => 'Transakcia bola uspesne vytvorena'
        ]);
    }

}