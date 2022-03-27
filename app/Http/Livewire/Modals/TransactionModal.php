<?php

namespace App\Http\Livewire\Modals;


use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\BelongsToSelect;
use App\Models\Post;

use Illuminate\Contracts\View\View;
use App\Http\Livewire\Modal;
use App\Models\Transaction;
use App\Models\TransactionType;

class TransactionModal extends Modal implements HasForms
{
	use InteractsWithForms; 

	public $name;

	public $transaction_type;

	public $transaction_type_id;

	public function mount(): void 
    {
        $this->form->fill();
    } 

	protected function getFormSchema(): array 
    {
		//dd(BelongsToSelect::make('transaction_type_id'));
        return [
            TextInput::make('name')->required(),
			BelongsToSelect::make('transaction_type_id')->relationship('transactionTypes', 'name'),
        ];
    } 

	protected function getFormModel(): string 
    {
        return Transaction::class;
    } 
 
    public function render(): View
    {
        return view('livewire.transaction-modal');
    }

}