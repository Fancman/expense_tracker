<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Modal;
use App\Models\AddressBook;

class AddressModal extends Modal
{
	public $name;

	protected $rules = [
        'name' => 'required|min:2',
    ];

	public function submit()
    {
        $this->validate();

		$user_id = (auth()->user() ? auth()->user()->id : 4);
 
        // Execution doesn't reach here if validation fails. 
        AddressBook::create([
            'name' => $this->name,
            'user_id' => $user_id,
        ]);
    }

    public function render()
    {
        return view('livewire.address-modal');
    }
}
