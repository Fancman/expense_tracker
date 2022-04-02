@extends('layouts.app')

@section('content')
	

	<div class="flex items-center justify-center">
		<div class="container mx-auto">
			<h1>Transakcie</h1>
			<button x-data="{}" x-on:click="window.livewire.emitTo('modals.transaction-modal', 'show')" class="text-indigo-500">Create transaction</button>
		</div>
		
	</div>
@endsection

@section('livewire-custom-scripts')
	@livewire('modals.transaction-modal')
@endsection