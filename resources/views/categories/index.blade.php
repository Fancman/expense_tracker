@extends('layouts.app')

@section('content')
	<button x-data="{}" x-on:click="window.livewire.emitTo('modals.category-modal', 'show')" class="bg-navy-blue text-white">Show Category modal</button>

	<div class="flex items-center justify-center">
		
	</div>
@endsection

@section('livewire-custom-scripts')
	@livewire('modals.category-modal')
@endsection