<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CategoryModal extends Component
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
        return view('livewire.category-modal');
    }
}
