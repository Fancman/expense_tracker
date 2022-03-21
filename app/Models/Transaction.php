<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

	protected $fillable = [
        'name',
        'value',
		'repeating',
    ];

	public function currency()
    {
        return $this->hasOne(Currency::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

	public function category()
    {
        return $this->hasOne(Category::class);
    }

	public function transactionType()
    {
        return $this->hasOne(TransactionType::class);
    }

	public function addressBook()
    {
        return $this->hasOne(AddressBook::class);
    }

	public function sourceAccount()
    {
        return $this->hasOne(Account::class, 'source_account_id');
    }

	public function endAccount()
    {
        return $this->hasOne(Account::class, 'end_account_id');
    }
	
}
