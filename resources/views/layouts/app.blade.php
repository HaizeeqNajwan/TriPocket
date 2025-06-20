<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TriPocket')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black/20 text-gray-900 backdrop-blur-md font-[Inter]">
    <div class="relative min-h-screen">
        <!-- Background Image -->
        <div class="absolute inset-0 bg-cover bg-center opacity-30 z-0" style="background-image: url('{{ asset('images/Background.png') }}');"></div>

        <!-- Foreground Content -->
        <div class="relative z-10">
            <!-- Navbar -->
            <nav class="fixed top-0 left-0 w-full bg-white/10 backdrop-blur-md shadow-md z-50 px-6 md:px-10 py-4 rounded-b-xl flex items-center justify-between">
    <!-- Left: Logo & Profile Dropdown -->
    <div class="relative flex items-center space-x-3">
        <!-- Logo / Toggle -->
        <button onclick="toggleDropdown(event)" class="logo-button text-xl md:text-2xl font-semibold tracking-wide focus:outline-none">
            <span class="text-yellow-500">Tri</span><span class="text-gray-900">P</span><span class="text-yellow-500">ocket</span>
        </button>

        <!-- Profile Dropdown -->
        <div id="profile-dropdown" class="hidden absolute top-14 left-0 bg-white rounded-lg shadow-lg w-60 border border-gray-100 z-50">
            <!-- User Info -->
            <div class="px-4 py-4 flex flex-col items-center bg-gray-50 rounded-t-lg">
                <img src="{{ Auth::user()->profile_picture ?? asset('images/default.jpg') }}" alt="Profile Picture" class="w-12 h-12 rounded-full border-2 border-white shadow-sm">
                <p class="text-sm text-gray-800 mt-2 font-semibold text-center">{{ Auth::user()->name }}</p>
            </div>
            <!-- Preferences -->
            <div class="px-4 py-3 border-t border-gray-100 text-left">
                <p class="text-xs text-gray-500 uppercase">Preferences</p>
                <p class="text-sm text-gray-700 mt-1">
                    {{ Auth::user()->preferences ? implode(', ', json_decode(Auth::user()->preferences, true)) : 'No preferences set' }}
                </p>
            </div>
            <!-- Edit Profile -->
            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">Edit Profile</a>
        </div>
    </div>

    <!-- Center: Nav Links -->
    <div class="absolute left-1/2 transform -translate-x-1/2 flex space-x-6">
        <a href="{{ url('/dashboard') }}" class="text-sm md:text-base text-gray-700 hover:text-gray-900 transition">Home</a>
        <a href="{{ route('trips.index') }}" class="text-sm md:text-base text-gray-700 hover:text-gray-900 transition">My Trips</a>
        <a href="{{ route('preferences.select') }}" class="text-sm md:text-base text-gray-700 hover:text-gray-900 transition">Preferences</a>
    </div>

    <!-- Right: Logout -->
    <div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm md:text-base font-medium rounded-lg hover:bg-gray-800 transition">
                Log Out
            </button>
        </form>
    </div>
</nav>

<div class="max-w-7xl mx-auto px-4 py-6">
                @yield('content')
            </div>
<script>
    function toggleDropdown(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('profile-dropdown');
        dropdown.classList.toggle('hidden');
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('profile-dropdown');
        const logoButton = document.querySelector('.logo-button');

        if (!dropdown.contains(event.target) && !logoButton.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>

</body>
</html>
