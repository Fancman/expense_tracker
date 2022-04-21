@extends('layouts.app')

@section('content')

	<div class="flex items-center">

		<button x-data="{}" x-on:click="window.livewire.emitTo('modals.account-modal', 'show')" class="bg-navy-blue text-white px-3 py-2 rounded">Vytvorit ucet</button>
		@livewire('refresh-prices-btn')

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
@endsection