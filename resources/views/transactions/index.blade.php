@extends('layouts.app')

@section('content')
	

	<div class="flex items-center">

		<button x-data="{}" x-on:click="window.livewire.emitTo('modals.transaction-modal', 'show')" class="bg-navy-blue text-white px-3 py-2 rounded">Zapísať transakciu</button>
		<button x-data="{}" x-on:click="window.livewire.emitTo('modals.import-modal', 'show')" class="bg-light-blue text-white px-3 py-2 rounded ml-5">Importovať VUB výpis</button>
		
	</div>

	<div class="flex items-center">

		<!-- Table -->
		<div class="bg-white w-full mx-auto py-6 px-5 mt-5 shadow-black border-slate-200 border rounded-sm">
			<div class="overflow-x-auto">
				@livewire('tables.transaction-table')
			</div>
		</div>	
		
	</div>
@endsection

@section('livewire-custom-scripts')
	@livewire('modals.transaction-modal')
	@livewire('modals.import-modal')
@endsection