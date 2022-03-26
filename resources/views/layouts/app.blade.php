<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=M+PLUS+1:wght@100;300;400;500;600;700&family=Source+Sans+Pro:wght@200;300;400;600;700&display=swap" rel="stylesheet">

	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<script src="{{ asset('js/app.js') }}" defer></script>

	@livewireStyles
</head>

<body>
	<div class="flex h-screen">
		<aside class="flex flex-col items-center w-96 z-20 bg-light-blue overflow-y-auto pt-14">
			<div class="text-4xl font-medium text-black">Expense Tracker</div>
			<div class="py-10 px-5">
				@auth
				<ul class="mt-3">					
					<li class="mb-4">
						<a class="text-navy-blue font-semibold text-2xl" href="#">Nastavenia</a>
					</li>
					<li class="mb-4">
						<a class="text-navy-blue font-semibold text-2xl" href="#">Kategórie</a>
					</li>
					<li class="mb-4">
						<a class="text-navy-blue font-semibold text-2xl" href="#">Účty</a>
					</li>
					<li class="mb-4">
						<a class="text-navy-blue font-semibold text-2xl" href="#">Transakcie</a>
					</li>
					<li class="mb-4">
						<a class="text-navy-blue font-semibold text-2xl" href="#">Štatistika</a>
					</li>
					<li class="mb-4">
						<a class="text-navy-blue font-semibold text-2xl" href="#">Adresár</a>
					</li>
					<li class="mb-4">
						<a class="text-navy-blue font-semibold text-2xl" href="{{ route('google.logout') }}">Odhlásiť sa</a>
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

		<div class="flex flex-col flex-1 w-full">
			@yield('content')
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
	
	@livewireScripts

	@livewire('category-modal')
</body>

</html>
