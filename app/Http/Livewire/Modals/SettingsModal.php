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
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;

class SettingsModal extends Modal implements HasForms
{
	use InteractsWithForms;

	//protected $listeners = ['refreshParent' => 'refresh'];

	public ?User $user = null;

	public $name = '';
	public $email = '';
	public $date_type = '';
	public $currency_id = '';
	public $remember_login = false;

	
	public function mount(): void 
    {
		$user_settings = [];

		$this->user = auth()->user() ?? User::find(4);
			
		$user_settings = [
			'name' => $this->user->name,
			'email' => $this->user->email,
			'date_type' => $this->user->date_type,
			'currency_id' => $this->user->currency_id,
			'remember_login' => $this->user->remember_login,
		];

        $this->form->fill($user_settings);
    } 

	protected function getFormSchema(): array 
    {
        return [            
			TextInput::make('name')->required()->label('Meno'),
			TextInput::make('email')->required()->label('Email'),
			TextInput::make('date_type')->required()->label('Preferovany format datumu'),
			Select::make('currency_id')->options(Currency::all()->pluck('name', 'id'))->label('Preferovana mena'),			
			Checkbox::make('remember_login')->label('Zapamatat prihlasenie'),			
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
        User::updateOrCreate(
			[
				'id' => $user_id,
			],
			[
				'name' => $this->name,            
				'email' => $this->email,
				'date_type' => $this->date_type,
				'currency_id' => $this->currency_id,
				'remember_login' => intval($this->remember_login)
			]
		);
		
		$this->reset();

		$message = 'Nastavenia boli ulozene';

		$this->dispatchBrowserEvent('settingsStore',
		[
            'type' => 'success',
            'message' => $message
        ]);

		$this->emit('refreshParent');

		$this->emit('showMessage');

		session()->flash('message', $message);	

		return redirect()->to('settings'); 
    }

}