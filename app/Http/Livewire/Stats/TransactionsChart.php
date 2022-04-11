<?php

namespace App\Http\Livewire\Stats;

//use App\Models\Expense;

use App\Models\Transaction;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Asantibanez\LivewireCharts\Models\RadarChartModel;
use Asantibanez\LivewireCharts\Models\TreeMapChartModel;
use Livewire\Component;

class TransactionsChart extends Component
{

	public $colors = [
        'PRIJEM' => '#f6ad55',
        'VYDAJ' => '#fc8181',
        'NAKUP' => '#90cdf4',
        'PREDAJ' => '#66DA26',
        'DLZOBA' => '#cbd5e0',
        'TRANSFER' => '#b8469a',
    ];

	public $firstRun = true;

	public $showDataLabels = false;

	public function render()
    {
		$expenses = Transaction::get();

		$pieChartModel = $expenses
		->groupBy('transactionType')
		->reduce(function ($pieChartModel, $data) {
			$transaction_type_code = $data->first()->transactionType->code;
			$transaction_type_name = $data->first()->transactionType->name;

			$value = $data->sum('value');

			return $pieChartModel->addSlice($transaction_type_name, $value, $this->colors[$transaction_type_code]);
		}, LivewireCharts::pieChartModel()
			//->setTitle('Expenses by Type')
			->setAnimated($this->firstRun)
			->setType('donut')
			->withOnSliceClickEvent('onSliceClick')
			//->withoutLegend()
			->legendPositionBottom()
			->legendHorizontallyAlignedCenter()
			->setDataLabelsEnabled($this->showDataLabels)
			->setColors(['#b01a1b', '#d41b2c', '#ec3c3b', '#f66665'])
		);

		$lineChartModel = $expenses
		//->where('transactionType.code', 'VYDAJ')
		->reduce(function ($lineChartModel, $data) use ($expenses) {
			$index = $expenses->search($data);

			$amountSum = $expenses->take($index + 1)->sum('value');

			if ($index == 6) {
				$lineChartModel->addMarker(7, $amountSum);
			}

			if ($index == 11) {
				$lineChartModel->addMarker(12, $amountSum);
			}

			return $lineChartModel->addPoint($data->name, $data->value, ['id' => $data->name]);
		}, LivewireCharts::lineChartModel()
			//->setTitle('Expenses Evolution')
			->setAnimated($this->firstRun)
			->withOnPointClickEvent('onPointClick')
			->setSmoothCurve()
			->setXAxisVisible(true)
			->setDataLabelsEnabled($this->showDataLabels)
			->sparklined()
		);

		return view('livewire.stats.transactions-chart')
            ->with([
                'pieChartModel' => $pieChartModel,
				'lineChartModel' => $lineChartModel
            ]);
	}
}