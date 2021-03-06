<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountValues extends Model
{
    use HasFactory;

	protected $table = 'user_accounts_values';

	/**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'value'
    ];

	public function getValueDateAttribute()
	{
		return $this->created_at->format('d.m.Y');
	}

}
