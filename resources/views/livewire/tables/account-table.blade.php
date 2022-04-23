<table class="table-auto w-full">
	<thead class="border-t border-b">
		<tr>
			<th class="font-semibold uppercase text-sm text-left py-3 pl-3">Nazov</th>
			<th class="font-semibold uppercase text-sm text-left py-3">Ikona</th>
			<th class="font-semibold uppercase text-sm text-left py-3">Hodnota</th>							
			<th class="font-semibold uppercase text-sm text-left py-3">Datum vytvorenia</th>
		</tr>
	</thead>
	<tbody class="text-sm border-b">
		@foreach($accounts as $account)
		<tr>
			<td class="px-2 py-3 whitespace-nowrap pl-3">
				<div class="text-left">{{ $account->name }}</div>
			</td>
			<td class="px-2 py-3 whitespace-nowrap">
				<div class="text-left">{{ $account->icon }}</div>
			</td>
			<td class="px-2 py-3 whitespace-nowrap">
				<div class="text-left">{{ $account->value }}</div>
			</td>
			<td class="px-2 py-3 whitespace-nowrap">
				<div class="text-left">{{ $account->created_at }}</div>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>