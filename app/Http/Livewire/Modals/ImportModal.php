<?php

namespace App\Http\Livewire\Modals;


use App\Models\User;
use App\Models\Account;
use App\Models\Currency;
use App\Models\Transaction;

use App\Http\Livewire\Modal;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;

class ImportModal extends Modal implements HasForms
{
	use InteractsWithForms;

	public $account_id = null;
	public $files = [];

	public function show()
	{
		$this->reset();
		$this->show = true;
	}


	protected function getFormSchema(): array 
    {
        return [            
			Select::make('account_id')
				->options(Account::where("user_id", (auth()->user() ? auth()->user()->id : 4))->pluck('name', 'id'))
				->label('Účet')->nullable(),	
			FileUpload::make('files')->label('Subor')->acceptedFileTypes(['text/csv']),	
        ];
    } 

	public function render(): View
    {
        return view('livewire.import-modal');
    }

	public function submit()
    {
        $this->validate();

		$user_id = (auth()->user() ? auth()->user()->id : 4);

		$this->reset();

		$message = 'Transakcie zo súbora boli nahrané';

		$this->dispatchBrowserEvent('transactionsStore',
		[
            'type' => 'success',
            'message' => $message
        ]);

		$this->emit('refreshParent');

		$this->emit('showMessage');

		session()->flash('message', $message);	
	}

}