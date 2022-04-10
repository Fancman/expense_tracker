<?php

namespace App\Http\Livewire\Modals;

use App\Http\Livewire\Modal;
use App\Models\AddressBook;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\TextInput;

class AddressBookModal extends Modal implements HasForms
{
	use InteractsWithForms;

	public $name;

	protected $rules = [
        'name' => 'required|min:2',
    ];

	public function mount(): void 
    {
        $this->form->fill();
    } 

	protected function getFormSchema(): array 
    {
        return [            
			TextInput::make('name')->required()->label('Nazov'),
			TextInput::make('IBAN')->required()->label('IBAN'),
        ];
    } 

	protected function getFormModel(): string 
    {
        return AddressBook::class;
    } 

	public function submit()
    {
        $this->validate();

		$user_id = (auth()->user() ? auth()->user()->id : 4);
 
        // Execution doesn't reach here if validation fails. 
        AddressBook::create([
            'name' => $this->name,
            'user_id' => $user_id,
            'IBAN' => $this->IBAN,
        ]);

		$this->dispatchBrowserEvent('addressBookStore',
		[
            'type' => 'success',
            'message' => 'Zaznam bol uspesne vytvoreny'
        ]);

		$this->emit('refreshParent');

		$this->emit('showMessage');

		session()->flash('message', 'Zaznam bol uspesne vytvoreny.');
    }

    public function render()
    {
        return view('livewire.address-book-modal');
    }
}
