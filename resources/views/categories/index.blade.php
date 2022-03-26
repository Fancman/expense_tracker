@extends('layouts.app')

@section('content')
	<button x-data="{}" x-on:click="window.livewire.emitTo('category-modal', 'show')" class="text-indigo-500">Show Category modal</button>
@endsection