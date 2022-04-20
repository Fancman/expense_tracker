@extends('layouts.app')

@section('content')

	<div class="flex items-center">

		<button x-data="{}" x-on:click="window.livewire.emitTo('modals.account-modal', 'show')" class="bg-navy-blue text-white px-3 py-2 rounded">Vytvorit ucet</button>
		<button x-data="{}" x-on:click="window.livewire.emitTo('refresh-prices', 'index')" class="bg-navy-blue text-white px-3 py-2 rounded  ml-5">Obnovit ceny</button>
		
	</div>

	<div class="flex items-center">

		<!-- Table -->
		<div class="bg-white w-full mx-auto py-6">
			<div class="overflow-x-auto">
				@livewire('tables.account-table')
			</div>
		</div>
	
		
	</div>
@endsection

@section('livewire-custom-scripts')
	@livewire('modals.account-modal')
	@livewire('refresh-prices')
@endsection