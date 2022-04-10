<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

	protected $fillable = [
        'name',
		'item_type_id',
        'quantity',
        'price',
		'currency_id',
        'fees',
		'fees_currency_id',
		'transaction_id'
    ];

	public function transaction()
    {
		return $this->belongsTo(Transaction::class, 'transaction_id');
    }

	public function itemType()
    {
		return $this->belongsTo(ItemType::class, 'item_type_id');
    }

	public function currency()
    {
		return $this->belongsTo(Currency::class, 'currency_id');
    }

	public function feesCurrency()
    {
		return $this->belongsTo(Currency::class, 'fees_currency_id');
    }
}
