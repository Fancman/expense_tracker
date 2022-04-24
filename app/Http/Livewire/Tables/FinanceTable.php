<?php

namespace App\Http\Livewire\Tables;

use App\Models\Account;
use App\Models\User;
use App\Models\AccountItem;
use Livewire\Component;
use Livewire\WithPagination;

class FinanceTable extends Component
{
	use WithPagination;

	protected $listeners = ['refreshParent' => 'render'];

	public function render()
    {
		$user_id = (auth()->user() ? auth()->user()->id : 4);


		$account_items = AccountItem::with('currency')
		->whereRelation('account.user', 'id', $user_id)
		->whereRelation('itemType', 'code', 'PENIAZE')
		->get()
		->sortByDesc('total_value', SORT_NATURAL);

		/*$test = $account_items->groupBy('currency.name')->map(function ($row) {
			return [
				'quantity' => $row->sum('quantity'),
				'average_buy_price' => $row->avg('average_buy_price'),
				'current_price' => $row->current_price,
				'total_value' => $row->sum('total_value')
			];
		});*/

        return view('livewire.tables.finance-table', [
			'account_items' => $account_items
		]);
    }
}