<?php

namespace App\Http\Controllers;

use App\Models\AccountItem;
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
        $account_items = AccountItem::with('itemType')
		->whereRelation('itemType', 'code', 'AKCIA')
		->get();
		
		foreach ($account_items as $account_item) {
			$account_item->updateStockItemPriceFromAPI();		
		}

		$account_items = AccountItem::with('itemType')
		->whereRelation('itemType', 'code', 'KRYPTOMENA')
		->get();
		
		foreach ($account_items as $account_item) {
			$account_item->updateCryptoItemPriceFromAPI();		
		}
    }
}
