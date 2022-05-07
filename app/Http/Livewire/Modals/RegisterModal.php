<?php

namespace App\Http\Livewire\Modals;

use App\Models\User;
use App\Models\Currency;
use App\Http\Livewire\Modal;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;

class RegisterModal extends Modal implements HasForms
{
	use InteractsWithForms;
	
	public $email = '', $password = '', $name = '';

	public function show()
	{
		$this->reset();
		$this->show = true;
	}

	protected function getFormSchema(): array 
    {
        return [            
			TextInput::make('name')->required()->label('Meno'),
			TextInput::make('email')->required()->label('Email')->email()->unique(),
			TextInput::make('password')->required()->label('Heslo')->password()
        ];
    } 

    public function render()
    {
        return view('livewire.register-modal');
    }

	private function resetInputFields(){
        $this->name = '';
        $this->email = '';
        $this->password = '';
    }

    public function submit()
    {
        $validatedDate = $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $this->password = Hash::make($this->password); 

		$currency = Currency::where('name', 'EUR')->first();

        User::create(['name' => $this->name, 'email' => $this->email, 'password' => $this->password, 'currency_id' => $currency->id]);

		$this->resetInputFields();

		$this->dispatchBrowserEvent('userRegister',
		[
            'type' => 'success',
            'message' => 'Uspesna registracia'
        ]);

		$this->emit('showMessage');

        session()->flash('message', 'Uspesna registracia');

    }
}
