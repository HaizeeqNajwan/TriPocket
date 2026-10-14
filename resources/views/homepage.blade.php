<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'TriPocket') }} - Sarawak Ecotourism</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-stone-100">

    <!-- Navigation Bar -->
    <nav class="fixed top-0 inset-x-0 z-50 flex items-center justify-between px-6 md:px-10 py-4 bg-white/40 backdrop-blur-lg shadow-md rounded-b-xl">
        <a href="{{ route('homepage') }}" class="text-xl md:text-2xl font-semibold tracking-wide">
            <span class="text-brand">TriP</span><span class="text-gray-600">ocket</span>
        </a>

        <div class="flex items-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="px-4 py-2 text-sm font-medium text-white bg-brand rounded-lg hover:bg-brand-dark transition">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="px-4 py-2 text-sm font-medium text-white bg-brand rounded-lg hover:bg-brand-dark transition">
                    Log In
                </a>
                <a href="{{ route('register') }}"
                   class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-500 rounded-lg hover:bg-brand hover:border-brand hover:text-white transition">
                    Register
                </a>
            @endauth
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative min-h-screen flex flex-col items-center justify-center text-center px-4 md:px-6 overflow-hidden">
        <div class="absolute inset-0 -z-10 bg-cover bg-center opacity-40"
             style="background-image: url('{{ asset('images/Background.png') }}');"></div>

        <div class="max-w-2xl">
            <p class="text-2xl md:text-3xl font-medium text-gray-900">Welcome to</p>
            <h1 class="mt-2 text-5xl md:text-6xl font-bold text-brand">TriP<span class="text-gray-600">ocket</span></h1>
            <p class="mt-4 text-lg text-gray-700">Plan your Sarawak ecotourism trip in minutes.</p>

            <a href="{{ auth()->check() ? route('dashboard') : route('login') }}"
               class="mt-8 inline-block px-8 py-3 font-medium text-gray-700 border border-gray-500 rounded-lg hover:bg-brand hover:border-brand hover:text-white transition">
                Get Started
            </a>
        </div>
    </header>

</body>
</html>
