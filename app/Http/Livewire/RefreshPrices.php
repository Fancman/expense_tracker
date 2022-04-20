<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Jobs\UpdatePrices;

class RefreshPrices extends Component
{

	protected $listeners = [
		'index' => 'index',
	];
	
	public function index()
	{
		$user_id = (auth()->user() ? auth()->user()->id : null);

		UpdatePrices::dispatch($user_id);
	}
}
