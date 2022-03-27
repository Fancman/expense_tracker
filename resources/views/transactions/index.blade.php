@extends('layouts.app')

@section('content')
	<button x-data="{}" x-on:click="window.livewire.emitTo('modals.transaction-modal', 'show')" class="text-indigo-500">Create transaction</button>

	<div class="flex items-center justify-center">
		
	</div>
@endsection

@section('livewire-custom-scripts')
	@livewire('modals.transaction-modal')
@endsection