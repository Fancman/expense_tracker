<?php

namespace App\Http\Livewire\Modals;


use App\Models\Account;
use App\Models\Currency;
use App\Models\Transaction;

use App\Http\Livewire\Modal;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;

class SettingsModal extends Modal implements HasForms
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
			TextInput::make('value')->required()->label('Hodnota'),
			TextInput::make('icon')->required()->label('Ikona'),
			Select::make('currency_id')->options(Currency::all()->pluck('name', 'id'))->label('Mena'),			
        ];
    } 

	protected function getFormModel(): string 
    {
        return Transaction::class;
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
        Account::create([
            'name' => $this->name,
            'user_id' => $user_id,
			'icon' => $this->icon,
			'currency_id' => $this->currency_id,
			'value' => $this->value
        ]);

		session()->flash('message', 'Ucet bol uspesne vytvoreny.');

		$this->dispatchBrowserEvent('accountStore',
		[
            'type' => 'success',
            'message' => 'Ucet bol uspesne vytvoreny'
        ]);
    }

}