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
<div x-on:categoryStore="show = false;">
	<x-modal wire:model="show">

		<form wire:submit.prevent="submit">
			<input type="text" placeholder="Nazov kategorie" wire:model="name">
			@error('name') <span class="error">{{ $message }}</span> @enderror
			
			<button type="submit">Ulozit</button>
		</form>		
	</x-modal>
</div>