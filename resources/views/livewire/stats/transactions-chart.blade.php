<div class="flex">
	<div class="flex w-2/4 p-5">
		<div class="shadow rounded p-4 border bg-white flex-1" style="height: 16rem;width:100%;">
			<livewire:livewire-pie-chart
				key="{{ $pieChartModel->reactiveKey() }}"
				:pie-chart-model="$pieChartModel"
			/>
		</div>
	</div>

	<div class="flex w-2/4 p-5">
		<div class="shadow rounded p-4 border bg-white" style="height: 16rem;width:100%;">
            <livewire:livewire-line-chart
                key="{{ $lineChartModel->reactiveKey() }}"
                :line-chart-model="$lineChartModel"
            />
        </div>
	</div>
</div>