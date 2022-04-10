<?php

namespace App\Http\Livewire\Modals;


use App\Models\User;
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

	public User $user;

	public function mount(): void 
    {
		$user_settings = [];

		if(auth()->user()){
			$this->user = auth()->user();
			$user_settings = [
				'name' => $this->user->name,
				'email' => $this->user->email,
				'date_type' => $this->user->date_type,
				'currency_id' => $this->user->currency_id,
			];
		}

        $this->form->fill($user_settings);
    } 

	protected function getFormSchema(): array 
    {
        return [            
			TextInput::make('name')->required()->label('Meno'),
			TextInput::make('email')->required()->label('Email'),
			TextInput::make('date_type')->required()->label('Preferovany format datumu'),
			Select::make('currency_id')->options(Currency::all()->pluck('name', 'id'))->label('Preferovana mena'),			
        ];
    } 

	protected function getFormModel(): string 
    {
        return User::class;
    } 
 
    public function render(): View
    {
        return view('livewire.settings-modal');
    }

	public function submit()
    {
        $this->validate();

		$user_id = (auth()->user() ? auth()->user()->id : 4);
 
        // Execution doesn't reach here if validation fails. 
        Account::updateOrCreate(
			[
				'user_id' => $user_id,
			],
			[
				'name' => $this->name,            
				'icon' => $this->icon,
				'currency_id' => $this->currency_id,
				'value' => $this->value
			]
		);

		$this->emit('showMessage');

		session()->flash('message', 'Nastavenia boli ulozene.');

		$this->dispatchBrowserEvent('settingsStore',
		[
            'type' => 'success',
            'message' => 'Nastavenia boli ulozene'
        ]);
    }

}