<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\GoogleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function(){
	return view('layouts.app');
})->name('home');

Route::get('/categories', function(){
	return view('categories.index');
});

Route::get('/address-book', function(){
	return view('address-book.index');
});

Route::resource('category', CategoryController::class);

Route::get('/livewire/message/category-modal', \App\Http\Livewire\Modals\CategoryModal::class);
Route::post('/livewire/message/category-modal', \App\Http\Livewire\Modals\CategoryModal::class);
Route::get('/livewire/message/address-modal', \App\Http\Livewire\Modals\AddressModal::class);
Route::post('/livewire/message/address-modal', \App\Http\Livewire\Modals\AddressModal::class);


/*Route::get('/', function () {
    return view('welcome');
});*/
 
// Google URL
Route::prefix('google')->name('google.')->group( function(){
    Route::get('logout', [GoogleController::class, 'logout'])->name('logout');
    Route::get('login', [GoogleController::class, 'login'])->name('login');
    Route::any('google-callback', [GoogleController::class, 'callbackFromGoogle'])->name('callback');
});

