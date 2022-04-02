@extends('layouts.app')

@section('content')
	

	<div class="flex items-center">

		<button x-data="{}" x-on:click="window.livewire.emitTo('modals.transaction-modal', 'show')" class="bg-navy-blue text-white px-3 py-2 rounded">Zapisat transakciu</button>
		
	</div>

	<div class="flex items-center">

		<!-- Table -->
		<div class="bg-white w-full mx-auto py-6">
			<div class="overflow-x-auto">
				<table class="table-auto w-full">
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
						<tr>
							<td class="px-2 py-3 whitespace-nowrap">
								<div class="text-left">Test</div>
							</td>
							<td class="px-2 py-3 whitespace-nowrap">
								<div class="text-left">Prijem</div>
							</td>
							<td class="px-2 py-3 whitespace-nowrap">
								<div class="text-left">Zabava</div>
							</td>
							<td class="px-2 py-3 whitespace-nowrap">
								<div class="text-left">22/01/2022</div>
							</td>
							<td class="px-2 py-3 whitespace-nowrap">
								<div class="text-left">20$</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	
		
	</div>
@endsection

@section('livewire-custom-scripts')
	@livewire('modals.transaction-modal')
@endsection