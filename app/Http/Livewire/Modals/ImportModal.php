<?php

namespace App\Http\Livewire\Modals;


use DateTime;
use App\Models\User;
use App\Models\Account;
use App\Models\Currency;

use App\Models\Transaction;
use App\Http\Livewire\Modal;
use App\Models\TransactionType;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Checkbox;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;

class ImportModal extends Modal implements HasForms
{
	use InteractsWithForms;

	public $account_id = null;
	public $files = [];
	public $showMessage = false;

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
				->label('Účet')->required(),	
			FileUpload::make('files')->label('Subor')->acceptedFileTypes(['text/csv'])->required(),	
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

		$files = $this->files;

		if(count($files)){
			foreach ($files as $file) {
				$filename = $file->store('files');

				$contents = Storage::get($filename);

				$data = str_getcsv($contents, "\n");

				foreach ($data as $i => $row) {
					if($i == 0){
						continue;
					}

					$exploded_row = str_getcsv($row, ",");

					$transaction_time = $exploded_row[1];
					$partner_name = $exploded_row[4];
					$amout = floatval(str_replace(' ', '', $exploded_row[5]));
					$currency = $exploded_row[6];
					$note = $exploded_row[11];
					$transaction_identification = $exploded_row[12];

					$transaction = Transaction::where('note_number', $transaction_identification)->latest('id')->first();

					if($transaction){
						continue;
					}

					$date = new DateTime($transaction_time);
					$formated_date = $date->format('Y-m-d H:i:s');

					$transaction_type = null;

					if ($amout > 0){
						$transaction_type = TransactionType::where('code', 'PRIJEM')->latest('id')->first();
					}else{
						$transaction_type = TransactionType::where('code', 'VYDAJ')->latest('id')->first();
					}

					$transaction_currency = Currency::where('name', $currency)->latest('id')->first();

					$transaction_data = [
						'name' => '[Import] ' . $partner_name,
						'user_id' => $user_id,
						'value' => abs($amout),
						'transaction_type_id' => $transaction_type->id,
						'currency_id' => $transaction_currency->id,
						'source_account_id' => $this->account_id,
						'end_account_id' => $this->account_id,
						'transaction_time' => $formated_date,
						'note' => $note,	
						'note_number' => $transaction_identification,
					];

					$transaction = Transaction::create($transaction_data);

					$transaction->createTransactionItems($transaction_type->code, null, null, null, false);
				}

				//dd($data);
			}
		}

		$this->reset();

		$message = 'Transakcie zo súbora boli nahrané';

		$this->dispatchBrowserEvent('importStore',
		[
            'type' => 'success',
            'message' => $message
        ]);

		$this->emit('refreshParent');

		$this->emit('showMessage');

		session()->flash('message', $message);
	}

}