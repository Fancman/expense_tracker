<div>
	<x-modal wire:model="show">
			<form>
				<input type="text" placeholder="Meno" wire:model="name">
				<button type="button" wire:click.prevent="store()">Save changes</button>
			</form>
	</x-modal>
</div>

