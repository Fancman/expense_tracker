<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PolygonIO\Rest\Rest;

class AccountItem extends Model
{
    use HasFactory;

	//protected $appends = ['total_value'];

	protected $fillable = [
        'name',
		'account_id',
		'item_type_id',
		'currency_id',
        'quantity',
        'average_buy_price',
        'current_price',
    ];

	private $currency_rates = [
		'EUR' => [
			'USD' => 1.09,
		],
		'USD' => [
			'EUR' => 0.92,
		]
	];

	public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

	public function itemType()
    {
        return $this->belongsTo(ItemType::class, 'item_type_id');
    }

	public function getTotalValueAttribute():  float
	{
		return floatval(floatval($this->current_price) * floatval($this->quantity));
	}

	public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }	

	public function getStock(){
		$api_key = config('services.polygon.key');
		$rest = new Rest($api_key);
		
		try {
			$response = $rest->stocks->previousClose->get($this->name);

			return [
				'response' => $response,
				'error' => null,
			];
		} catch (\Throwable $th) {
			$msg = $th->getMessage();
			
			return [
				'response' => null,
				'error' => $msg,
			];
		}
	}

	public function getCrypto(){
		$api_key = config('services.polygon.key');
		$rest = new Rest($api_key);
		
		try {
			$response = $rest->crypto->previousClose->get($this->name);

			return [
				'response' => $response,
				'error' => null,
			];
		} catch (\Throwable $th) {
			$msg = $th->getMessage();
			
			return [
				'response' => null,
				'error' => $msg,
			];
		}
	}

	public function updateStockWithSameName($price){
		$account_items = AccountItem::with('itemType')
		->whereRelation('itemType', 'code', 'AKCIA')
		->where('name', $this->name)
		->update(['current_price' => $price]);;
	}

	public function updateCryptoWithSameName($price){
		$account_items = AccountItem::with('itemType')
		->whereRelation('itemType', 'code', 'KRYPTOMENA')
		->where('name', $this->name)
		->update(['current_price' => $price]);;
	}

	public function convertCurrency($amout, $from, $to){
		if( $from != $to ){
			if( isset($this->currency_rates[$to][$from]) ){
				$amout = $this->currency_rates[$to][$from] * floatval($amout);
			}
		}

		return $amout;
	}

	public function updateStockItemPriceFromAPI(){
		$stock_data = $this->getStock();

		$stock_data_response = $stock_data['response'];
		$stock_data_error = $stock_data['error'];

		if( is_null($stock_data_response) ){
			return;
		}

		if( isset($stock_data_response['resultsCount']) && intval($stock_data_response['resultsCount']) > 0 ){
			$price = $stock_data_response['results'][0]['c'];
			$float_price = floatval($price);

			$this->updateAccountValue(
				(floatval($this->current_price) - floatval($float_price)) * $this->quantity,
				'USD'
			);

			$this->current_price = floatval($float_price);
			$this->save();

			$this->updateStockWithSameName($float_price);
			
		}
	}

	public function updateCryptoItemPriceFromAPI(){
		$stock_data = $this->getCrypto();

		$stock_data_response = $stock_data['response'];
		$stock_data_error = $stock_data['error'];

		if( is_null($stock_data_response) ){
			return;
		}

		if( isset($stock_data_response['resultsCount']) && intval($stock_data_response['resultsCount']) > 0 ){
			$price = $stock_data_response['results'][0]['c'];
			$float_price = floatval($price);

			$this->updateAccountValue(
				(floatval($this->current_price) - floatval($float_price)) * $this->quantity,
				'USD'
			);

			$this->current_price = floatval($float_price);
			$this->save();

			$this->updateCryptoWithSameName($float_price);
		}
	}

	public function updateAccountValue($value, $currency_name = 'USD'){
		$value_converted = $this->convertCurrency($value, $currency_name, $this->currency->name);

		if($value_converted > 0)
		{
			$this->account->value = floatval($this->account->value) - $value_converted;
			$this->account->save();
		}
		else if($value_converted < 0)
		{
			$this->account->value = floatval($this->account->value) + $value_converted;
			$this->account->save();
		}
	}
}
