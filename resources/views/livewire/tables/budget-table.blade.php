<table class="table-auto w-full">
	<thead class="border-t border-b">
		<tr>
			<th class="font-semibold uppercase text-sm text-left py-3">Kategoria</th>
			<th class="font-semibold uppercase text-sm text-left py-3">Dlzka</th>
			<th class="font-semibold uppercase text-sm text-left py-3">Zaciatok</th>
			<th class="font-semibold uppercase text-sm text-left py-3">Mnozstvo penazi</th>
			<th class="font-semibold uppercase text-sm text-left py-3 w-1/4">Akcie</th>
		</tr>
	</thead>
	<tbody class="text-sm border-b">
		@foreach($budgets as $budget)
		<tr>
			<td class="px-2 py-3 whitespace-nowrap">
				<div class="text-left">{{ $budget->category->name }}</div>
			</td>
			<td class="px-2 py-3 whitespace-nowrap">
				<div class="text-left">{{ $budget->budget_period }}</div>
			</td>
			<td class="px-2 py-3 whitespace-nowrap">
				<div class="text-left">{{ $budget->start_time }}</div>
			</td>
			<td class="px-2 py-3 whitespace-nowrap">
				<div class="text-left">{{ $budget->amount }}</div>
			</td>
			<td class="px-2 py-3 whitespace-nowrap w-1/4">
				<button x-data="{}" x-on:click="window.livewire.emitTo('modals.budget-modal', 'delete', {{ $budget->id }})" class="bg-red text-white mr-1 px-3 py-2 rounded">Vymazat</button>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>