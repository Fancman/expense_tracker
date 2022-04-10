<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function login()
    {
        return Socialite::driver('google')->redirect();
    }

	public function logout()
    {
        auth()->logout();
		
		return redirect()->route('home');
    }

	public function callbackFromGoogle()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();
			$is_user = User::where('email', $user->getEmail())->first();
			$currency = Currency::where('name', 'EUR')->first();

            if(!$is_user){

                $saveUser = User::updateOrCreate([
                    'google_id' => $user->getId(),
                ],[
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
					'currency_id' => $currency->id,
                ]);
            }else{
                $saveUser = User::where('email',  $user->getEmail())->update([
                    'google_id' => $user->getId(),
                ]);
                $saveUser = User::where('email', $user->getEmail())->first();
            }


            Auth::loginUsingId($saveUser->id, $saveUser->remember_login);

            return redirect()->route('home');

		} catch (\Throwable $th) {
            throw $th;
        }
	}
}
