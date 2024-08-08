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
		<link href="https://fonts.googleapis.com/css2?family=Archivo:wght@200;400;500;600;700&display=swap" rel="stylesheet"> 

        <!-- Styles -->
        @vite('resources/css/app.scss')
        @vite('resources/css/tailwind.css')
        @livewireStyles
        @yield('styles')
    </head>
    <body>
        <div id="app" class="min-h-screen flex flex-col justify-between">
			@include('partials.header')

            <div class="wrap flex-1">
                <div class="container mx-auto px-4 py-10">
                    @yield('content')
                </div>
            </div>

            @include('partials.footer')
        </div>

        <!-- Scripts -->
        @livewireScripts
        @vite('resources/js/app.js')
        @yield('scripts')
    </body>
</html>