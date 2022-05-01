<?php

namespace App\Http\Livewire\Stats;

//use App\Models\Expense;

use Livewire\Component;
use App\Models\AccountItem;
use App\Models\Transaction;
use App\Models\AccountValues;
use Illuminate\Support\Facades\DB;
use Asantibanez\LivewireCharts\Models\LineChartModel;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;

class AccountValuesChart extends Component
{

	public $firstRun = true;

	public $showDataLabels = true;

	public function render()
    {
		$account_values = AccountValues::get();

		$user_id = (auth()->user() ? auth()->user()->id : 4);

		$account_items = AccountItem::select(
			DB::raw(
				'account_items.name,
				SUM(quantity * current_price) as total_value_sum'
			)
		)
		->join('currencies', 'account_items.currency_id', '=', 'currencies.id')
		->whereRelation('account.user', 'id', $user_id)
		->groupBy('account_items.name','currencies.name')
		->get()
		->sortByDesc('total_value_sum', SORT_NATURAL);

		$lineChartModel = $account_values
		->where('user_id',  (auth()->user() ? auth()->user()->id : 4))
		->reduce(function ($lineChartModel, $data) use ($account_values) {
			return $lineChartModel->addPoint($data->value_date, $data->value, []);
		}, (new LineChartModel())
			->setTitle('Vyvoj portfolia')
			//->setAnimated($this->firstRun)
			//->withOnPointClickEvent('onPointClick')
			->setSmoothCurve()
			->setXAxisVisible(true)
			->setYAxisVisible(true)
			//->withDataLabels(true)
			//->setDataLabelsEnabled($this->showDataLabels)
			//->sparklined()
		);

		$pieChartModel = $account_items
		->reduce(function ($pieChartModel, $data) {
			$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
   			$color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];

			return $pieChartModel->addSlice($data->name, floatval($data->total_value_sum), $color);
		}, LivewireCharts::pieChartModel()
			//->setTitle('Expenses by Type')
			->setAnimated($this->firstRun)
			->setType('donut')
			->withOnSliceClickEvent('onSliceClick')
			//->withoutLegend()
			->legendPositionBottom()
			->legendHorizontallyAlignedCenter()
			->setDataLabelsEnabled(false)
		);

		return view('livewire.stats.account-values-chart')
            ->with([
				'pieChartModel' => $pieChartModel,
				'lineChartModel' => $lineChartModel
            ]);
	}
}