<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

	protected $fillable = [
		'user_id',
		'category_id',
		'amount',
		'start_time',
		'budget_period',
		'reached'
    ];

	public function user()
    {
        return $this->belongsTo(User::class);
    }

	public function category()
    {
		return $this->belongsTo(Category::class, 'category_id');
    }
}
