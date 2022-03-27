<?php

namespace App\Http\Livewire\Modals;

use App\Http\Livewire\Modal;
use App\Models\Category;

class CategoryModal extends Modal
{
	public $name;

	protected $rules = [
        'name' => 'required|min:2',
    ];

	private function resetInputFields(){
        $this->name = '';
    }

	public function submit()
    {
        $this->validate();

		$user_id = (auth()->user() ? auth()->user()->id : 4);
 
        // Execution doesn't reach here if validation fails. 
        Category::create([
            'name' => $this->name,
            'user_id' => $user_id,
        ]);

		session()->flash('message', 'Kategoria bola uspesne vytvorena.');

        $this->resetInputFields();

		$this->dispatchBrowserEvent('categoryStore',
		[
            'type' => 'success',
            'message' => 'Kategoria bola uspesne vytvorena'
        ]);
    }

    public function render()
    {
        return view('livewire.category-modal');
    }
}
