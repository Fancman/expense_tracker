@extends('layouts.app')

@section('content')

	<div class="flex items-center">

		<button x-data="{}" x-on:click="window.livewire.emitTo('modals.settings-modal', 'show')" class="bg-navy-blue text-white px-3 py-2 rounded">Zmenit nastavenia</button>
		
	</div>

	<div class="flex items-center">

		<div class="bg-white w-full mx-auto py-6">
			<div class="overflow-x-auto">
				<div class="flex">
					<div class="mb-3 xl:w-64">
						<div class="flex">
							<span class="text-sm border-2 rounded-l px-4 py-2 bg-gray-100 whitespace-no-wrap">Meno:</span>
							<input type="text" class="form-input appearance-none
							block
							w-full
							px-3
							py-1.5
							text-base
							font-normal
							text-gray-700
							bg-white bg-clip-padding bg-no-repeat
							border border-solid border-gray-300
							rounded
							transition
							ease-in-out
							m-0
							focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"  wire:model="name" value="{{ $user->name }}"/>
						</div>
					</div>

					<div class="mb-3 xl:w-64 ml-5">

						<div class="flex">
							<span class="text-sm border-2 rounded-l px-4 py-2 bg-gray-100 whitespace-no-wrap">Email:</span>
							<input type="text" class="form-input appearance-none						
							block
							w-full
							px-3
							py-1.5
							text-base
							font-normal
							text-gray-700
							bg-white bg-clip-padding bg-no-repeat
							border border-solid border-gray-300
							rounded
							transition
							ease-in-out
							m-0
							focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"  wire:model="name" value="{{ $user->email }}"/>
						</div>
					</div>

					<div class="mb-3 xl:w-125 ml-5">
						<div class="flex">
							<span class="text-sm border-2 rounded-l px-4 py-2 bg-gray-100 whitespace-no-wrap w-64">Preferovany datum:</span>
							<input type="text" class="form-input appearance-none
							block
							w-full
							px-3
							py-1.5
							text-base
							font-normal
							text-gray-700
							bg-white bg-clip-padding bg-no-repeat
							border border-solid border-gray-300
							rounded
							transition
							ease-in-out
							m-0
							focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"  wire:model="name" value="{{ $user->date_type }}"/>
						</div>
					</div>
				</div>

				<div class="flex">					

					<div class="mb-3 xl:w-125 ml-5">
						<div class="flex items-center">
							<span class="text-sm border-2 rounded-l px-4 py-2 bg-gray-100 whitespace-no-wrap w-64 mr-4">Zapamatat prihlasenie:</span>
							<input class="w-5 h-5 form-check-input appearance-none border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left cursor-pointer" type="checkbox" value="" {{ $user->remember_login ? 'checked' : ''}}>
						</div>
					</div>

					<div class="mb-3 xl:w-125 ml-5">
						<div class="flex items-center">
							<span class="text-sm border-2 rounded-l px-4 py-2 bg-gray-100 whitespace-no-wrap w-64 mr-4">Hlavna mena:</span>
							<select class="form-select appearance-none
							block
							w-full
							px-3
							py-1.5
							text-base
							font-normal
							text-gray-700
							bg-white bg-clip-padding bg-no-repeat
							border border-solid border-gray-300
							rounded
							transition
							ease-in-out
							m-0
							focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" aria-label="Default select example" wire:model="filterTransactionType" >
								<option>NULL</option>
								@foreach($currencies as $currency)
									<option {{ $user->currency_id == $currency->id ? 'selected' : '' }} value="{{ $currency->id }}">{{ $currency->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>	
		
	</div>
@endsection


@section('livewire-custom-scripts')
	@livewire('modals.settings-modal')
@endsection