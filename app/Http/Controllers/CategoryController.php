<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $name = $request->input('name');
 
        //
    }

	public function update(Request $request, $id)
    {
        //
    }

	public function destroy(Request $request, $id)
    {
        //
    }
}
