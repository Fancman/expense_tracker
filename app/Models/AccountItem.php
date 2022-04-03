<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountItem extends Model
{
    use HasFactory;

	protected $fillable = [
        'name',
		'account_id',
		'item_type_id',
		'currency_id',
        'quantity',
        'average_buy_price',
        'current_price',
    ];

	public function account()
    {
        return $this->hasOne(Account::class);
    }

	public function itemType()
    {
        return $this->hasOne(ItemType::class);
    }

	public function currency()
    {
        return $this->hasOne(Currency::class);
    }
}
