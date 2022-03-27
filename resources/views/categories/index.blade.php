@extends('layouts.app')

@section('content')
	<button x-data="{}" x-on:click="window.livewire.emitTo('modals.category-modal', 'show')" class="text-indigo-500">Show Category modal</button>
	<button x-data="{}" x-on:click="window.livewire.emitTo('modals.address-modal', 'show')" class="text-indigo-500">Show Address modal</button>

	<div class="flex items-center justify-center">
		<livewire:categories/>
	</div>
@endsection

@section('livewire-custom-scripts')
	@livewire('modals.category-modal')
	@livewire('modals.address-modal')	
@endsection