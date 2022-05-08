<?php

namespace App\Http\Controllers;

use App\Jobs\UpdatePrices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    protected $title = 'Účty';

	public function index()
    {
        return view('accounts.index', [
			'title' => $this->title,
		]);
    }

	public function refresh_prices()
    {
		$user_id = (auth()->user() ? auth()->user()->id : null);
		echo 'User_id: ' . $user_id;
        //UpdatePrices::dispatch($user_id)->onQueue('host-laravel-d13296:worker_0');
        //UpdatePrices::dispatch($user_id);
    }
}
