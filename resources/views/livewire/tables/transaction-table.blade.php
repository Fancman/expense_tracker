<div>
	<div class="flex">
		<input type="text"  class="mb-5 mr-5" placeholder="Hľadať v názve" wire:model="searchTerm" />
		<div class="ml-5 mb-3 xl:w-96">
			<select class="form-select appearance-none
			block
			w-full
			px-3
			py-1.5
			text-base
			font-normal
			text-gray-700
			bg-white bg-clip-padding bg-no-repeat
			border border-solid border-gray-300
			rounded
			transition
			ease-in-out
			m-0
			focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" aria-label="Default select example" wire:model="filterCategory" >
				<option value="null" selected>Filtruj podla kategorie</option>
				@foreach($categories as $category)
					<option value="{{ $category->id }}">{{ $category->name }}</option>
				@endforeach
			</select>
		</div>
		<div class="ml-5 mb-3 xl:w-96">
			<select class="form-select appearance-none
			block
			w-full
			px-3
			py-1.5
			text-base
			font-normal
			text-gray-700
			bg-white bg-clip-padding bg-no-repeat
			border border-solid border-gray-300
			rounded
			transition
			ease-in-out
			m-0
			focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" aria-label="Default select example" wire:model="filterTransactionType" >
				<option value="null" selected>Filtruj podla typu transakcie</option>
				@foreach($transaction_types as $transaction_type)
					<option value="{{ $transaction_type->id }}">{{ $transaction_type->name }}</option>
				@endforeach
			</select>
		</div>
		<div
			x-data=""			
			x-init="new Pikaday({ field: $refs.fromDate, 'format': 'YYYY-MM-DD', firstDay: 1, });"
			class="w-50 ml-10">
			
			<input
				wire:model.lazy="fromDate"
				x-ref="fromDate"
				type="text"
				class="w-full pl-4 pr-10 py-2 leading-none rounded-lg shadow-sm focus:outline-none border-gray-300 text-gray-600 font-medium focus:ring focus:ring-blue-600 focus:ring-opacity-50" placeholder="Datum od"
			/>

		</div>
		<div
			x-data=""			
			x-init="new Pikaday({ field: $refs.toDate, 'format': 'YYYY-MM-DD', firstDay: 1, });"
			class="w-50 ml-10">			
			<input
				wire:model.lazy="toDate"
				x-ref="toDate"
				type="text"
				class="w-full pl-4 pr-10 py-2 leading-none rounded-lg shadow-sm focus:outline-none border-gray-300 text-gray-600 font-medium focus:ring focus:ring-blue-600 focus:ring-opacity-50" placeholder="Datum do"
			/>

		</div>
	</div>

	<table class="table-auto w-full mb-6">
		<thead class="border-t border-b">
			<tr>
				<th class="font-semibold uppercase text-sm text-left py-3">Nazov</th>
				<th class="font-semibold uppercase text-sm text-left py-3">Typ transakcie</th>
				<th class="font-semibold uppercase text-sm text-left py-3">Kategoria</th>
				<th class="font-semibold uppercase text-sm text-left py-3">Datum transakcie</th>
				<th class="font-semibold uppercase text-sm text-left py-3">Hodnota</th>
				<th class="font-semibold uppercase text-sm text-left py-3">Akcie</th>
			</tr>
		</thead>
		<tbody class="text-sm border-b">
			@foreach($transactions as $transaction)
			<tr>
				<td class="px-2 py-3 whitespace-nowrap">
					<div class="text-left">{{ $transaction->name }}</div>
				</td>
				<td class="px-2 py-3 whitespace-nowrap">
					<div class="text-left">{{  $transaction->transactionType ? $transaction->transactionType->name : 'NULL' }}</div>
				</td>
				<td class="px-2 py-3 whitespace-nowrap">
					<div class="text-left">{{ $transaction->category ? $transaction->category->name : 'NULL' }}</div>
				</td>
				<td class="px-2 py-3 whitespace-nowrap">
					<div class="text-left">{{ $transaction->transaction_time }}</div>
				</td>			
				<td class="px-2 py-3 whitespace-nowrap">
					<div class="text-left">{{ $transaction->value }}</div>
				</td>
				<td class="px-2 py-3 whitespace-nowrap">
					<button x-data="{}" x-on:click="window.livewire.emitTo('modals.transaction-modal', 'edit', {{ $transaction->id }})" class="bg-purple text-white mr-1 px-3 py-2 rounded">Upravit</button>
					<button x-data="{}" x-on:click="window.livewire.emitTo('modals.transaction-modal', 'delete', {{ $transaction->id }})" class="bg-red-500 text-white mr-1 px-3 py-2 rounded">Vymazat</button>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>

	{{ $transactions->links() }}
</div>