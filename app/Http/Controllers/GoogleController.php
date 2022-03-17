<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function loginWithGoogle()
    {
		dd("test");
        return Socialite::driver('google')->redirect();
    }

	public function callbackFromGoogle()
    {
        try {
            $user = Socialite::driver('google')->user();
		} catch (\Throwable $th) {
            throw $th;
        }
	}
}
