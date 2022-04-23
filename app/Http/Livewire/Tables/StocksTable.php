<?php

namespace App\Http\Livewire\Tables;

use App\Models\Account;
use App\Models\User;
use App\Models\AccountItems;
use Livewire\Component;
use Livewire\WithPagination;

class StocksTable extends Component
{
	use WithPagination;

	protected $listeners = ['refreshParent' => 'render'];

	public function render()
    {
		$user_id = (auth()->user() ? auth()->user()->id : 4);

		$user = User::find($user_id)->latest()->first();

		$accounts = $user->accounts();

		$account_items = $accounts->accountItems()
		->whereRelation(
			'itemType', 'code', '=', 'AKCIA'
		)->get();

        return view('livewire.tables.stocks-table', [
			'account_items' => $account_items
		]);
    }
}