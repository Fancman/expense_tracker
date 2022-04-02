<?php

namespace App\Http\Livewire\Tables;

use App\Models\Account;
use Livewire\Component;
use Livewire\WithPagination;

class AccountTable extends Component
{
	use WithPagination;

	protected $listeners = ['refreshParent' => '$render'];

	public function render()
    {
        return view('livewire.tables.account-table', [
			'accounts' => Account::latest()->paginate(10)
		]);
    }
}