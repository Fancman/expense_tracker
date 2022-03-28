@extends('layouts.app')

@section('content')
	<button x-data="{}" x-on:click="window.livewire.emitTo('modals.address-book-modal', 'show')" class="text-indigo-500">Create address book record</button>

	<div class="flex items-center justify-center">
		
	</div>
@endsection

@section('livewire-custom-scripts')
	@livewire('modals.address-book-modal')
@endsection