<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    protected $title = 'Nastavenia';

	public function index()
    {
		$user = (auth()->user() ? auth()->user() : User::find(4));

        return view('settings.index', [
			'title' => $this->title,
			'user' => $user
		]);
    }
}
