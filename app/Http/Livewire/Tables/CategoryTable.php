<?php

namespace App\Http\Livewire\Tables;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryTable extends Component
{
	use WithPagination;

	protected $listeners = ['refreshParent' => 'render'];

	public function render()
    {
		$user_id = (auth()->user() ? auth()->user()->id : 4);

        return view('livewire.tables.category-table', [
			'categories' => Category::where('user_id', $user_id)->orderBy('id', 'DESC')->paginate(10)
		]);
    }
}