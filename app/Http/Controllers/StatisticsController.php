<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    protected $title = '┼átatistika';

	public function index()
    {
        return view('statistics.index', [
			'title' => $this->title,
		]);
    }
}
