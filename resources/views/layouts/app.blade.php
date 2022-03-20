<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Desktop sidebar -->
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
</body>

</html>