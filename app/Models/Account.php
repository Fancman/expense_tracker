<?php

namespace App\Models;

use App\Models\User;
use App\Models\Currency;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;

	/**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'value',
		'icon',
		'currency_id',
		'user_id',
		'current_value'
    ];

    public function accountItems()
    {
        return $this->hasMany(AccountItem::class);
    }

	public function sourceAccountTransactions()
    {
		return $this->hasMany(Transaction::class, 'source_account_id', 'id');
        //return Transaction::where('source_account_id', $this->id)->get();
    }

	public function endAccountTransactions()
    {
		return $this->hasMany(Transaction::class, 'end_account_id', 'id');
        //return Transaction::where('end_account_id', $this->id)->get();
    }

	public function delete_transactions()
    {
        $source_transactions = $this->sourceAccountTransactions;
        $end_transactions = $this->sourceAccountTransactions;

		foreach($source_transactions as $transaction) {
			$transaction_type = $transaction->transactionType;
			$transaction->deleteTransaction($transaction_type->code);
		}

		foreach($end_transactions as $transaction) {
			$transaction_type = $transaction->transactionType;
			$transaction->deleteTransaction($transaction_type->code);
		}
    }


    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

	public function calculate_current_value(){
		$account_items = AccountItem::select(
			DB::raw('
			currencies.name as currency_name,
			SUM(quantity * account_items.average_buy_price) as total_account_value,
			SUM(quantity * account_items.current_price) as current_total_account_value')
		)
		->join('currencies', 'account_items.currency_id', '=', 'currencies.id')
		->where('account_items.account_id', $this->id)
		->groupBy('currencies.name')
		->get();

		$total_value = 0;
		$total_current_value = 0;

		foreach($account_items as $account_item){	
			$total_account_value = $account_item->total_account_value;
			$current_total_account_value = $account_item->current_total_account_value;
			$currency_name = $account_item->currency_name;

			if($currency_name != $this->currency->name){
				$current_total_account_value = $account_item->convertCurrency($current_total_account_value, $currency_name, $this->currency->name);
				$total_account_value = $account_item->convertCurrency($total_account_value, $currency_name, $this->currency->name);				
			}

			$total_current_value += $current_total_account_value;
			$total_value += $total_account_value;
		}

		return [
			'total_value' => $total_value,
			'current_value' => $total_current_value,
		];
	}
}
