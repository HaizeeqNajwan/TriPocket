<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TriPocket') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-stone-100">
        <div class="fixed inset-0 -z-10 bg-cover bg-center opacity-40"
             style="background-image: url('{{ asset('images/Background.png') }}');"></div>

        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10">
            <a href="{{ route('homepage') }}" class="text-3xl font-semibold tracking-wide">
                <span class="text-brand">TriP</span><span class="text-gray-600">ocket</span>
            </a>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white/90 backdrop-blur-sm shadow-xl rounded-2xl">
                {{ $slot }}
            </div>

            <a href="{{ route('homepage') }}" class="mt-6 text-sm text-gray-600 hover:text-brand transition">
                &larr; Back to home
            </a>
        </div>
    </body>
</html>
