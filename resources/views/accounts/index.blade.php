@extends('layouts.app')

@section('content')

	<div class="flex items-center">

		<button x-data="{}" x-on:click="window.livewire.emitTo('modals.account-modal', 'show')" class="bg-navy-blue text-white px-3 py-2 rounded">Vytvorit ucet</button>
		@livewire('refresh-prices-btn')

	</div>

	<div class="flex items-center">

		<!-- Table -->
		<div class="bg-white w-full mx-auto my-6">
			<div class="overflow-x-auto">
				@livewire('tables.account-table')
			</div>
		</div>
		
	</div>

	<div class="grid grid-cols-2 gap-4">
		<div class="shadow-black bg-white border-slate-200 border rounded-sm">
			<header class="py-4 px-5 border-b">
				<h2 class="font-semibold text-lg">Akcie</h2>
			</header>
			<div class="p-3">
				<div class="overflow-x-auto">
					@livewire('tables.stocks-table')
				</div>
			</div>
		</div>
		<div>09</div>
	</div>
@endsection

@section('livewire-custom-scripts')
	@livewire('modals.account-modal')
@endsection