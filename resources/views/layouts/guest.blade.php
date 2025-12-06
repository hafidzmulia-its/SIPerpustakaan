<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Literasik') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('assets/bukus.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('assets/bukus.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('assets/bukus.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
        <script src="//unpkg.com/alpinejs" defer></script>
        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
    
        <div class="min-h-screen flex flex-col bg-white justify-center items-center pt-6 sm:pt-0"
             style="background-image: url('{{ asset('storage/assets/bg-guest.png') }}'); background-size: cover; background-position: center; background-color: #F3EAE2;">
            
         
            {{ $slot }}
        </div>
    </body>
</html>
