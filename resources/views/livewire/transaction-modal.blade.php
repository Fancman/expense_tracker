<div>
	<x-modal wire:model="show">
		<form wire:submit.prevent="submit">
			
			{{ $this->form }}

			<div class="flex justify-center mt-5">
				<button class="text-center px-4 py-2 bg-blue rounded-md text-white" type="submit">Ulozit</button>
			</div>
		</form>		
	</x-modal>
</div>