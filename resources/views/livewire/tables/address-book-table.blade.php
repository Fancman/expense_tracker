<table class="table-auto w-full">
	<thead class="border-t border-b">
		<tr>
			<th class="font-semibold uppercase text-sm text-left py-3">Nazov</th>
			<th class="font-semibold uppercase text-sm text-left py-3">IBAN</th>
		</tr>
	</thead>
	<tbody class="text-sm border-b">
		@foreach($address_book_records as $address)
		<tr>
			<td class="px-2 py-3 whitespace-nowrap">
				<div class="text-left">{{ $address->name }}</div>
			</td>
			<td class="px-2 py-3 whitespace-nowrap">
				<div class="text-left">{{ $address->IBAN }}</div>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>