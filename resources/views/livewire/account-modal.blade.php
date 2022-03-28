@if (session()->has('message'))
	<div 
	x-data="{ showMessage: false }"
    x-init="() => {
		setTimeout(() => showMessage = true, 500);
		setTimeout(() => showMessage = false, 5000);
    }"
    x-show="showMessage" 
	class="fixed bottom-5 left-7 p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
		<span class="font-medium">{{ session('message') }}</span>
	</div>
@endif
<div x-on:accountStore="show = false;">
	<x-modal wire:model="show">
		<form wire:submit.prevent="submit">
			
			{{ $this->form }}

			<div class="flex justify-center mt-5">
				<button class="text-center px-4 py-2 bg-blue rounded-md text-white" type="submit">Ulozit</button>
			</div>
		</form>		
	</x-modal>
</div>