<?php

namespace App\Http\Livewire\Tables;

use App\Models\Account;
use App\Models\User;
use App\Models\AccountItem;
use Livewire\Component;
use Livewire\WithPagination;

class CryptoTable extends Component
{
	use WithPagination;

	protected $listeners = ['refreshParent' => 'render'];

	public function render()
    {
		$user_id = (auth()->user() ? auth()->user()->id : 4);


		$account_items = AccountItem::whereRelation('account.user', 'id', $user_id)
		->whereRelation('account.user', 'id', $user_id)
		->whereRelation('itemType', 'code', 'KRYPTOMENA')
		->get()
		->sortByDesc('total_value', SORT_NATURAL);

        return view('livewire.tables.crypto-table', [
			'account_items' => $account_items
		]);
    }
}