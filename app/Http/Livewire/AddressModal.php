<?php

namespace App\Http\Livewire;

use Livewire\Component;

class AddressModal extends Component
{
	public $show = false;

	protected $listeners = [
		'show' => 'show',
	];
	
	public function show()
	{
		$this->show = true;
	}

    public function render()
    {
        return view('livewire.address-modal');
    }
}
