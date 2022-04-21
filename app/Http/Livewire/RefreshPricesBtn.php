<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class RefreshPricesBtn extends Component
{
	public $refreshing_prices = false;

	public function refreshing(){
		$this->refreshing_prices = true;
	}

	public function mount()
    {
		$user_id = (auth()->user() ? auth()->user()->id : null);

		if($user_id){			

			$user = User::find($user_id);

			if($this->refreshing_prices == true && $user->fetching_prices == false){
				$this->emitTo('tables.account-table', 'refreshParent');
			}

			$this->refreshing_prices = $user->fetching_prices;
		}
    }

    public function render()
    {
		$this->mount();
        return view('livewire.refresh-prices-btn');
    }
}
