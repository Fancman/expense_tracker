<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" style="height:100%;">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Expense Tracker</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=M+PLUS+1:wght@100;300;400;500;600;700&family=Source+Sans+Pro:wght@200;300;400;600;700&display=swap" rel="stylesheet">

	<style>[x-cloak] { display: none !important; }</style>

	@livewireStyles
		
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">

	<script src="{{ asset('vendor/livewire-charts/app.js') }}" defer></script>
</head>

<body class="antialiased h-full">
	<div class="flex h-full">
		<aside class="flex flex-col items-center w-56 2xl:w-96 z-20 bg-cod-grey overflow-y-auto pt-14 h-full fixed">
			<div class="text-4xl font-medium text-white text-center">Expense Tracker</div>
			<div class="py-10 px-5">
				@auth
				<ul class="mt-3">			
					<li class="mb-5 flex items-center">
						<svg class="w-8 h-8 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
						<a class="font-semibold text-2xl
						{{ (request()->is('transactions')) ? 'text-white' : 'text-white' }}
						" href="{{ route('transactions') }}">Transakcie</a>
					</li>
					<li class="mb-5 flex items-center">
						<svg class="w-8 h-8 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
						<a class="text-white font-semibold text-2xl" href="{{ route('accounts') }}">Účty</a>
					</li>
					<li class="mb-5 flex items-center">
						<svg class="w-8 h-8 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
						<a class="text-white font-semibold text-2xl" href="{{ route('categories') }}">Kategórie</a>
					</li>
					<li class="mb-5 flex items-center">
						<svg class="w-8 h-8 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
						<a class="text-white font-semibold text-2xl" href="{{ route('statistics') }}">Štatistika</a>
					</li>
					<li class="mb-5 flex items-center">
						<svg class="w-8 h-8 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
						<a class="text-white font-semibold text-2xl" href="{{ route('address-book') }}">Adresár</a>
					</li>
					<li class="mb-5 flex items-center">
						<svg class="w-8 h-8 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
						<a class="text-white font-semibold text-2xl" href="{{ route('settings') }}">Nastavenia</a>
					</li>
					<li class="mb-4">
						<a class="text-white font-semibold text-2xl" href="{{ route('google.logout') }}">Odhlásiť sa</a>
					</li>					
				</ul>
				@endauth
				@guest
					<a href="{{ route('google.login') }}" type="button" class="text-center mt-16 px-6 py-4 bg-blue font-semibold text-2xl rounded-md text-white">
						Prihlásiť sa pomocou Google
					</a>
				@endguest
			</div>
		</aside>

		<div class="flex flex-col flex-1 w-[calc(100%-14rem)] 2xl:w-full pl-[14rem] 2xl:pl-[24rem] bg-light-gray overflow-y-auto">
			<header class="px-8 border-b">
				<div class="flex items-center justify-between h-16">
					@auth
						<h1 class="text-4xl font-semibold">{{ $title }}</h1>
					@endauth
					@auth
					<div lang="flex items-center">
						<span>{{ Auth::user()->name }}</span>
					</div>
					@endauth
				</div>
			</header>
			<div class="container mx-auto px-8 py-8">
				@auth
					@yield('content')
				@endauth
			</div>			
		</div>
		
	</div>
	
	<!--
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900">
        <aside class="z-20 hidden w-64 overflow-y-auto bg-white dark:bg-gray-800 md:block flex-shrink-0"></aside>

        <div class="flex flex-col flex-1 w-full">
            <header class="z-10 py-4 bg-white shadow-md dark:bg-gray-800">
				<div class="container flex items-center justify-between h-full px-6 mx-auto text-purple-600 dark:text-purple-300">
					@guest
						<a href="{{ route('google.login') }}">Login</a>
					@endguest
					@auth
					<span>{{ auth()->user()->name  }}</span>
						<a href="{{ route('google.logout') }}">Logout</a>
					@endauth
				</div>
            </header>
        </div>
    </div>
	-->

	<script src="{{ asset('js/app.js') }}" defer></script>

	@livewireScripts

	@stack('scripts')

	@yield('livewire-custom-scripts')
	
</body>

</html>
