<?php

namespace App\Http\Livewire\Modals;

use App\Models\Category;
use App\Http\Livewire\Modal;

use Illuminate\Support\Facades\DB;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;

class CategoryModal extends Modal implements HasForms
{
	use InteractsWithForms;

	public $name = '';
	public $icon = '';

	public ?Category $category = null;

	protected $rules = [
        'name' => 'required|min:2',
    ];

	public function delete($id){
		$this->category = Category::find($id);

		DB::table('transactions')->where('category_id', $id)->update(['category_id' => null]);

		$this->category->delete();

		$this->reset();

		$this->emit('refreshParent');

		$this->emit('showMessage');

		session()->flash('message', 'Kategoria bola uspesne vymazana.');	
	}

	public function edit($id){
		$this->category = Category::find($id);

		$this->form->fill(
			[
				'name' => $this->category->name,
				'icon' => $this->category->icon,
			]
		);

		$this->show = true;
	}

	public function mount(): void 
    {
        $this->form->fill();
    } 

	protected function getFormSchema(): array 
    {
        return [            
			TextInput::make('name')->required()->label('Nazov')->unique(),
			TextInput::make('icon')->nullable()->label('Ikona'),
        ];
    } 

	/*protected function getFormModel(): string 
    {
        return $this->category;
    } */

	public function submit()
    {
		$updating = false;

        $this->validate();

		if( isset($this->category) ){
			$this->category->name = $this->name;
			$this->category->icon = $this->icon;

			$this->category->save();

			$updating = true;
		}

		$user_id = (auth()->user() ? auth()->user()->id : 4);
 
        // Execution doesn't reach here if validation fails. 
    
		if( !isset($this->category) ){
			Category::create([
				'name' => $this->name,
				'user_id' => $user_id,
			]);
	
		}

        $this->reset();

		$message = ($updating ? 'Kategoria bola uspesne upravena' : 'Kategoria bola uspesne vytvorena');

		$this->dispatchBrowserEvent('categoryStore',
		[
            'type' => 'success',
            'message' => $message
        ]);

		$this->emit('refreshParent');

		$this->emit('showMessage');

		session()->flash('message', $message);	
    }

    public function render()
    {
        return view('livewire.category-modal');
    }
}
