<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Be.Family Assistenza Clienti') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;700&display=swap" rel="stylesheet"> 

        <!-- Styles -->
        @vite('resources/css/app.scss')
        @yield('styles')
    </head>
    <body>
        <div id="app">
			<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-secondary">
                <div>
                    <a href="/">
                        <img src="{{ Vite::asset('resources/images/bf-logo-reg-small.png') }}" alt="Be.Family Assistenza Clienti" class="h-30">
                    </a>
                </div>
    
                <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <!-- Scripts -->
        @vite('resources/js/app.js')
        @yield('scripts')
    </body>
</html>