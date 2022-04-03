<div>
	<table class="table-auto w-full mb-6">
		<thead class="border-t border-b">
			<tr>
				<th class="font-semibold uppercase text-sm text-left py-3">Nazov</th>
				<th class="font-semibold uppercase text-sm text-left py-3">Typ transakcie</th>
				<th class="font-semibold uppercase text-sm text-left py-3">Kategoria</th>
				<th class="font-semibold uppercase text-sm text-left py-3">Datum transakcie</th>
				<th class="font-semibold uppercase text-sm text-left py-3">Hodnota</th>
			</tr>
		</thead>
		<tbody class="text-sm border-b">
			@foreach($transactions as $transaction)
			<tr>
				<td class="px-2 py-3 whitespace-nowrap">
					<div class="text-left">{{ $transaction->name }}</div>
				</td>
				<td class="px-2 py-3 whitespace-nowrap">
					<div class="text-left">{{ $transaction->transactionType->name }}</div>
				</td>
				<td class="px-2 py-3 whitespace-nowrap">
					<div class="text-left">{{ $transaction->category->name }}</div>
				</td>
				<td class="px-2 py-3 whitespace-nowrap">
					<div class="text-left">{{ $transaction->transaction_time }}</div>
				</td>			
				<td class="px-2 py-3 whitespace-nowrap">
					<div class="text-left">{{ $transaction->value }}</div>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>

	{{ $transactions->links() }}
</div>