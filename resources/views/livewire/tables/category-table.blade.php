<table class="table-auto w-full">
	<thead class="border-t border-b">
		<tr>
			<th class="font-semibold uppercase text-sm text-left py-3">Nazov</th>
			<th class="font-semibold uppercase text-sm text-left py-3">Ikona</th>
			<th class="font-semibold uppercase text-sm text-left py-3 w-1/4">Akcie</th>
		</tr>
	</thead>
	<tbody class="text-sm border-b">
		@foreach($categories as $category)
		<tr>
			<td class="px-2 py-3 whitespace-nowrap">
				<div class="text-left">{{ $category->name }}</div>
			</td>
			<td class="px-2 py-3 whitespace-nowrap">
				<div class="text-left">{{ $category->icon }}</div>
			</td>
			<td class="px-2 py-3 whitespace-nowrap w-1/4">
				<button x-data="{}" x-on:click="window.livewire.emitTo('modals.category-modal', 'edit', {{ $category->id }})" class="bg-purple text-white mr-1 px-3 py-2 rounded">Upravit</button>
				<button x-data="{}" x-on:click="window.livewire.emitTo('modals.category-modal', 'delete', {{ $category->id }})" class="bg-red-500 text-white mr-1 px-3 py-2 rounded">Vymazat</button>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>