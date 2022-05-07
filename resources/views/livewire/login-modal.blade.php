<div x-on:userLogin="show = false;">
	<x-modal wire:model="show">
		<form wire:submit.prevent="submit">
			
			{{ $this->form }}

			<div class="flex justify-center mt-5">
				<button class="text-center px-4 py-2 bg-blue rounded-md text-white" type="submit">Ulozit</button>
			</div>
		</form>	
		
		@if (session()->has('error'))
			<div class="bottom-5 left-7 p-4 mt-5 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
				<span class="font-medium">{{ session('error') }}</span>
			</div>
        @endif

		@if (session()->has('message'))
			<div class="bottom-5 left-7 p-4 mt-5 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
				<span class="font-medium">{{ session('message') }}</span>
			</div>
        @endif

		

	</x-modal>
</div>