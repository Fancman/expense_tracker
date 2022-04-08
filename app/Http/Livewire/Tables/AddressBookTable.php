<?php

namespace App\Http\Livewire\Tables;

use App\Models\AddressBook;
use Livewire\Component;
use Livewire\WithPagination;

class AddressBookTable extends Component
{
	use WithPagination;

	protected $listeners = ['refreshParent' => 'render'];

	public function render()
    {
		$user_id = (auth()->user() ? auth()->user()->id : 4);

        return view('livewire.tables.address-book-table', [
			'address_book_records' => AddressBook::where('user_id', $user_id)->latest()->paginate(10)
		]);
    }
}