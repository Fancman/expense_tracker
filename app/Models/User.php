<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Currency;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

	protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
		'date_type',
		'google_id',
		'remember_login',
		'password',
		'currency_id',
		'fetching_prices'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
    ];

	/**
     * Get the phone associated with the user.
     */
    public function currency()
    {
        return $this->hasOne(Currency::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }


	public function calculate_user_value(){
		$total_value = 0;

		foreach ($this->accounts as $account) {
			$calc = $account->calculate_current_value();
			$total_value += $calc['current_value'];
		}

		return $total_value;
	}

}
