<?php

namespace App\Http\Livewire\Modals;

use App\Models\User;
use App\Models\Currency;
use App\Http\Livewire\Modal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;

class LoginModal extends Modal implements HasForms
{
	use InteractsWithForms;
	
	public $email = '', $password = '';

	public function show()
	{
		$this->reset();
		$this->show = true;
	}

	protected function getFormSchema(): array 
    {
        return [
			TextInput::make('email')->required()->label('Email')->email()->unique(),
			TextInput::make('password')->required()->label('Heslo')->password()
        ];
    } 

    public function render()
    {
        return view('livewire.login-modal');
    }

	private function resetInputFields(){
        $this->email = '';
        $this->password = '';
    }

    public function submit()
    {
		$validatedDate = $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if(Auth::attempt(array('email' => $this->email, 'password' => $this->password))){
                session()->flash('message', "Prihlasenie prebehlo uspesne");
				$this->resetInputFields();
				return redirect()->route('home');
        }else{
			$this->resetInputFields();
            session()->flash('error', 'Zle meno aleno heslo');
        }

    }
}
