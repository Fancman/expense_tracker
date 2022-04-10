@extends('layouts.app')

@section('content')	

	<div class="flex items-center">

	</div>

	<div class="flex items-center">

		<!-- Table -->
		<div class="bg-white w-full mx-auto py-6">
			<div class="overflow-x-auto">
				@livewire('stats.transactions-chart')
			</div>
		</div>	
		
	</div>
@endsection


