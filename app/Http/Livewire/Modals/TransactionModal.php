<?php

namespace App\Http\Livewire\Modals;


use Closure;
use App\Models\Post;
use App\Models\Account;
use App\Models\Currency;
use App\Models\AddressBook;

use App\Models\Transaction;
use App\Http\Livewire\Modal;
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

	public $name;

	public $transaction_type;

	public $transaction_type_id;

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

}