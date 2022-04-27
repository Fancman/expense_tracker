<?php

namespace App\Http\Livewire\Tables;

use App\Models\User;
use App\Models\Account;
use Livewire\Component;
use App\Models\AccountItem;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class FinanceTable extends Component
{
	use WithPagination;

	protected $listeners = ['refreshParent' => 'render'];

	public function render()
    {
		$user_id = (auth()->user() ? auth()->user()->id : 4);


		$account_items = AccountItem::select(DB::raw('currencies.name AS name, SUM(quantity) as total_quantity'))
		->join('currencies', 'account_items.currency_id', '=', 'currencies.id')
		->whereRelation('account.user', 'id', $user_id)
		->whereRelation('itemType', 'code', 'PENIAZE')
		->groupBy('currencies.name')
		->get()
		->sortByDesc('total_quantity', SORT_NATURAL);

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