<div 
	x-data="{
		show: @entangle($attributes->wire('model'))
	}"
	x-show="show"
	x-on:keydown.escape.window="show = false"
	x-cloak
	class="fixed inset-0 overflow-y-auto px-4 py-6 md:py-24 sm:px-0 z-40"
>
	<div class="fixed inset-0 transform">
		<div 
			x-show="show" 
			x-on:click="show = false"
			class="absolute inset-0 bg-gray-500 opacity-75"
		></div>

		<div x-show="show" class="flex items-center justify-center h-full">
			<div class="bg-white rounded-lg transform sm:w-full sm:mx-auto max-w-lg align-middle px-7 py-5 relative overflow-auto h-full">
				{{ $slot }}
			</div>
		</div>

	</div>
</div>