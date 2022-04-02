<?php

namespace App\Http\Livewire\Tables;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionTable extends Component
{
	use WithPagination;

	public function render()
    {
        return view('livewire.tables.transaction-table', [
			'transactions' => Transaction::latest()
			//->join('categories', 'categories.id', '=', 'transactions.category_id')
			->paginate(10)
		]);
    }
}