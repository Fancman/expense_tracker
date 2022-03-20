<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Currency;
use App\Models\User;

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
    ];


    public function currency()
    {
        return $this->hasOne(Currency::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
