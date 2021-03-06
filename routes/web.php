<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\SettingsController;

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
	$title = 'Home';

	return redirect()->route('transactions');
	//return view('layouts.app');
})->name('home');

Route::get('/categories', function(){
	$title = 'Kategórie';

	return view('categories.index', compact('title'));
})->name('categories');

Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');

Route::get('/address-book', function(){
	$title = 'Adresár';

	return view('address-book.index', compact('title'));
})->name('address-book');

Route::get('/accounts', [AccountController::class, 'index'])->name('accounts');
Route::get('/accounts/refresh_prices', [AccountController::class, 'refresh_prices'])->name('refresh_prices');

Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');

Route::get('/settings', [SettingsController::class, 'index'])->name('settings');


Route::resource('category', CategoryController::class);

Route::get('/livewire/message/category-modal', \App\Http\Livewire\Modals\CategoryModal::class);
Route::post('/livewire/message/category-modal', \App\Http\Livewire\Modals\CategoryModal::class);

Route::get('/livewire/message/address-modal', \App\Http\Livewire\Modals\AddressBookModal::class);
Route::post('/livewire/message/address-modal', \App\Http\Livewire\Modals\AddressBookModal::class);

Route::get('/livewire/message/transaction-modal', \App\Http\Livewire\Modals\TransactionModal::class);
Route::post('/livewire/message/transaction-modal', \App\Http\Livewire\Modals\TransactionModal::class);

Route::get('/livewire/message/account-modal', \App\Http\Livewire\Modals\AccountModal::class);
Route::post('/livewire/message/account-modal', \App\Http\Livewire\Modals\AccountModal::class);

Route::get('/livewire/message/settings-modal', \App\Http\Livewire\Modals\SettingsModal::class);
Route::post('/livewire/message/settings-modal', \App\Http\Livewire\Modals\SettingsModal::class);


/*Route::get('/', function () {
    return view('welcome');
});*/
 
// Google URL
Route::prefix('google')->name('google.')->group( function(){
    Route::get('logout', [GoogleController::class, 'logout'])->name('logout');
    Route::get('login', [GoogleController::class, 'login'])->name('login');
    Route::any('google-callback', [GoogleController::class, 'callbackFromGoogle'])->name('callback');
});

