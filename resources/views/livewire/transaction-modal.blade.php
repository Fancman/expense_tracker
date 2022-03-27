<div>
	<x-modal wire:model="show">
		<form wire:submit.prevent="submit">
			{{ $this->form }}
			
			<button type="submit">Ulozit</button>
		</form>		
	</x-modal>
</div>