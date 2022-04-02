<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    protected $title = 'Účty';

	public function index()
    {
        return view('accounts.index', [
			'title' => $this->title,
		]);
    }
}
