@extends('layouts.app')

@section('content')
	<button x-data="{}" x-on:click="window.livewire.emitTo('modals.settings-modal', 'show')" class="text-indigo-500">Zmenit nastavenia</button>

	<div class="flex items-center justify-center">
		
	</div>
@endsection

@section('livewire-custom-scripts')
	@livewire('modals.settings-modal')
@endsection