<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    protected $title = 'Statistika';

	public function index()
    {
        return view('statistics.index', [
			'title' => $this->title,
		]);
    }
}
