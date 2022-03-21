<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

	protected $fillable = [
        'name',
        'quantity',
        'price',
        'fees',
    ];

	public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

	public function itemType()
    {
        return $this->hasOne(ItemType::class);
    }

	public function currency()
    {
        return $this->hasOne(Currency::class);
    }

	public function feesCurrency()
    {
        return $this->hasOne(Transaction::class);
    }
}
