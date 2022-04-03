<?php

namespace App\Http\Livewire\Tables;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionTable extends Component
{
	use WithPagination;

	public $searchTerm;

	public function render()
    {
		$query = '%'.$this->searchTerm.'%';

        return view('livewire.tables.transaction-table', [
			'transactions' => Transaction::where(function($sub_query){
				$sub_query->where('name', 'like', '%'.$this->searchTerm.'%');
			})->paginate(10)
		]);
    }
}