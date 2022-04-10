<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Modal extends Component
{
    public $show = false;
	public $showMessage = false;
	

	protected $listeners = [
		'show' => 'show',
		'edit' => 'edit'
	];
	
	public function show()
	{
		$this->show = true;
	}

    public function render()
    {
        //
    }
}
