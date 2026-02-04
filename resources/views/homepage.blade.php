<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TriPocket - Homepage</title>
    @vite(['resources/css/app.css', 'resources/css/homepage.css', 'resources/js/app.js'])
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="flex items-center space-x-2 md:space-x-4">
            <span class="text-xl md:text-2xl font-semibold tracking-wide">
                <span class="text-pink-900">TriP</span><span class="text-gray-600">ocket</span>
            </span>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('login') }}" class="button-primary">Log In</a>
            <a href="{{ route('register') }}" class="button-secondary">Register</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative min-h-screen flex flex-col items-center justify-center text-center px-4 md:px-6">
        <!-- Background Image -->
        <div class="hero-bg"></div>

        <!-- Content -->
        <div class="relative max-w-2xl">
            <p class="text-2xl md:text-3xl font-medium text-gray-900">Welcome to</p>
            <h1 class="hero-title">TriP<span>ocket</span></h1>
            <a href="{{ route('login') }}" class="mt-6 block button-secondary">
                Get Started
            </a>
        </div>
    </div>

</body>
</html>
