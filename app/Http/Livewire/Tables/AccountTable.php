<?php

namespace App\Http\Livewire\Tables;

use App\Models\Account;
use Livewire\Component;
use Livewire\WithPagination;

class AccountTable extends Component
{
	use WithPagination;

	protected $listeners = ['refreshParent' => 'render'];

	public function render()
    {
		$user_id = (auth()->user() ? auth()->user()->id : 4);

        return view('livewire.tables.account-table', [
			'accounts' => Account::where('user_id', $user_id)->latest()->paginate(10)
		]);
    }
}