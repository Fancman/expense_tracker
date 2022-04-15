<?php

namespace App\Http\Livewire\Modals;


use Closure;
use App\Models\User;
use App\Models\Budget;
use App\Models\Account;

use App\Models\Category;
use App\Models\Currency;
use App\Models\Transaction;
use App\Http\Livewire\Modal;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;

class BudgetModal extends Modal implements HasForms
{
	use InteractsWithForms;

	//public $user_id = '';
	public ?Budget $budget = null;
	public $amount = 0;
	public $start_time = '';
	public $budget_period = '';
	public $category_id = '';

	public function show()
	{
		$this->reset();
		$this->show = true;
	}

	protected function getFormSchema(): array 
    {
        return [            
			Select::make('budget_period')
				->options([
					'deň' => 'deň',
					'týždeň' => 'týždeň',
					'mesiac' => 'mesiac',
					'rok' => 'rok',
				]
			)->label('Dĺžka platnosti')->required(),
			DateTimePicker::make('start_time')->label('Začiatok platnosti')->nullable(),
			Select::make('category_id')
				->options(Category::where("user_id", (auth()->user() ? auth()->user()->id : 4))->pluck('name', 'id'))
				->label('Kategoria')->nullable()->required(),
			TextInput::make('amount')->numeric()->required()->label('Množstvo')->gt(0),
        ];
    } 

	public function render(): View
    {
        return view('livewire.budget-modal');
    }

	public function delete($id){
		$this->budget = Budget::find($id);

		$this->budget->delete();

		$this->reset();

		$this->emit('refreshParent');

		$this->emit('showMessage');

		session()->flash('message', 'Budget bol uspesne vymazany.');	
	}

	public function submit()
    {
        $this->validate();

		$user_id = (auth()->user() ? auth()->user()->id : 4);

		$budget_data = [
			'budget_period' => $this->budget_period,
			'start_time' => (empty($this->start_time) ? Carbon::now(): $this->start_time),
			'category_id' => $this->category_id,
			'amount' => $this->amount,
			'user_id' => $user_id,
		];

		$this->budget = Budget::create($budget_data);

		$this->reset();

		$message = 'Budget bol vytvorený';

		$this->dispatchBrowserEvent('budgetStore',
		[
            'type' => 'success',
            'message' => $message
        ]);

		$this->emit('refreshParent');

		$this->emit('showMessage');

		session()->flash('message', $message);	
	}

}