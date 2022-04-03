<?php

namespace App\Http\Livewire\Tables;

use App\Models\Category;
use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionTable extends Component
{
	use WithPagination;

	public $searchTerm;
	public $filterCategory;
	public $fromDate;
	public $toDate;

	public function render()
    {
		$user_id = (auth()->user() ? auth()->user()->id : 4);

        return view('livewire.tables.transaction-table', [
			'transactions' => 
				Transaction::where('user_id', $user_id)
				->where(function($sub_query){
					$sub_query->where('name', 'like', '%'.$this->searchTerm.'%');

					if( !is_null($this->filterCategory) ){
						$sub_query->where('category_id', $this->filterCategory);
					}

					if( !is_null($this->fromDate) ){
						$sub_query->whereDate('transaction_time', '>=' , $this->fromDate);
					}

					if( !is_null($this->toDate) ){
						$sub_query->whereDate('transaction_time', '<=' , $this->toDate);
					}

				})->paginate(10),
			'categories' => Category::where('user_id', $user_id)->get(),
		]);
    }
}