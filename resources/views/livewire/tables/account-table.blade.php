<table class="table-auto w-full">
	<thead class="border-t border-b">
		<tr>
			<th class="font-semibold uppercase text-sm text-left py-3 pl-3">Nazov</th>
			<th class="font-semibold uppercase text-sm text-left py-3">Povodna hodnota</th>							
			<th class="font-semibold uppercase text-sm text-left py-3">Aktualna hodnota</th>							
			<th class="font-semibold uppercase text-sm text-left py-3">Rast portfolia</th>							
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
				<div class="text-left">{{ $account->value }}</div>
			</td>
			<td class="px-2 py-3 whitespace-nowrap">
				<div class="text-left">{{ $account->current_value }}</div>
			</td>
			<td class="px-2 py-3 whitespace-nowrap">
				@if (($account->current_value / $account->value * 100) == 100)
					<div class="text-left">0 %</div>
				@elseif (($account->current_value / $account->value * 100) > 100)
					<div class="text-left font-semibold">{{ $account->current_value - $account->value }} {{ $account->currency->name }} <span class="text-green-500 ml-2">({{ round(($account->current_value / $account->value * 100) - 100, 2) }} %)</span></div>
				@elseif (($account->current_value / $account->value * 100) < 100)
					<div class="text-left font-semibold">{{ $account->current_value - $account->value }} {{ $account->currency->name }} <span class="text-red-500 ml-2">({{ round(($account->current_value / $account->value * 100) - 100, 2) }} %)</span></div>
				@endif				
			</td>
			<td class="px-2 py-3 whitespace-nowrap">
				<div class="text-left">{{ $account->created_at }}</div>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>