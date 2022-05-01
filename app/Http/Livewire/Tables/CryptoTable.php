<?php

namespace App\Http\Livewire\Tables;

use App\Models\User;
use App\Models\Account;
use Livewire\Component;
use App\Models\AccountItem;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class CryptoTable extends Component
{
	use WithPagination;

	protected $listeners = ['refreshParent' => 'render'];

	public function render()
    {
		$user_id = (auth()->user() ? auth()->user()->id : 4);


		$account_items = AccountItem::select(
			DB::raw(
				'account_items.name,
				currencies.name as currency_name,
				SUM(quantity) as total_quantity,
				AVG(average_buy_price) as average_buy_price,
				AVG(current_price) as current_price,
				SUM(quantity * current_price) as total_value_sum'
			)
		)
		->join('currencies', 'account_items.currency_id', '=', 'currencies.id')
		->whereRelation('account.user', 'id', $user_id)
		->whereRelation('itemType', 'code', 'KRYPTOMENA')
		->groupBy('account_items.name','currencies.name')
		->get()
		->sortByDesc('total_value_sum', SORT_NATURAL);

        return view('livewire.tables.crypto-table', [
			'account_items' => $account_items
		]);
    }
}