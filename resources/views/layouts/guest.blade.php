<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased custom-background">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <!-- Tripocket Logo -->
            <div>
            <span class="text-xl md:text-2xl font-semibold tracking-wide">
                <span class="text-pink-900">TriP</span><span class="text-gray-600">ocket</span>
            </span>
            </div>

            <!-- Login Box -->
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white/90 dark:bg-gray-800/90 shadow-md overflow-hidden sm:rounded-lg backdrop-blur-sm">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>