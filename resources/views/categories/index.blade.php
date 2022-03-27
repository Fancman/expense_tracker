@extends('layouts.app')

@section('content')
	<button x-data="{}" x-on:click="window.livewire.emitTo('category-modal', 'show')" class="text-indigo-500">Show Category modal</button>
	<button x-data="{}" x-on:click="window.livewire.emitTo('address-modal', 'show')" class="text-indigo-500">Show Address modal</button>
@endsection

@section('livewire-custom-scripts')
	@livewire('category-modal')
	@livewire('address-modal')	
@endsection