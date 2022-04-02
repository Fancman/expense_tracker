<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $title = 'Transakcie';

	public function index()
    {
        return view('transactions.index', [
			'title' => $this->title,
		]);
    }
}
