<nav class="fixed top-0 left-0 w-full px-6 md:px-10 py-4 bg-white/10 backdrop-blur-lg shadow-md z-50 flex items-center justify-between rounded-b-xl">
    <!-- Logo with Dropdown -->
    <div class="relative flex items-center space-x-2 md:space-x-4">
        <button onclick="toggleDropdown(event)" class="logo-button text-xl md:text-2xl font-semibold tracking-wide focus:outline-none">
            <span class="text-pink-900">TriP</span><span class="text-stone-600">ocket</span>
        </button>
        <div id="profile-dropdown" class="hidden absolute top-12 left-0 bg-white rounded-lg shadow-lg py-2 w-56 z-50 border border-gray-100">
            <!-- Profile Section -->
            <div class="px-4 py-3 flex flex-col items-center bg-gray-50 rounded-t-lg">
                <img src="{{ Auth::user()->profile_photo_url }}" 
                     alt="Profile Photo" 
                     class="w-12 h-12 rounded-full border-2 border-white shadow-sm">
                <p class="text-sm text-gray-700 mt-2 font-medium">{{ Auth::user()->name }}</p>
            </div>

            <!-- Preferences Display -->
            <div class="px-4 py-3 border-t border-gray-100">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Preferences</p>
                    <a href="{{ route('profile.edit') }}" class="text-xs text-blue-600 hover:text-blue-800">Edit</a>
                </div>
                <div class="flex flex-wrap gap-1 mt-1">
                    @php
                    $preferences = auth()->user()->preferences ?? [];
                    @endphp
                    
                    @if(is_array($preferences) && !empty($preferences))
                        @foreach($preferences as $preference)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $preference }}
                            </span>
                        @endforeach
                    @else
                        <p class="text-sm text-gray-500">None selected</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Center Links - Updated for better mobile responsiveness -->
    <div class="hidden md:flex absolute left-1/2 transform -translate-x-1/2 space-x-6">
        <a href="{{ route('dashboard') }}" 
           class="text-lg text-gray-700 hover:text-gray-900 transition-all duration-300 {{ request()->routeIs('dashboard') ? 'font-medium text-gray-900' : '' }}">
           Home
        </a>
        <a href="{{ route('itineraries.index') }}" 
           class="text-lg text-gray-700 hover:text-gray-900 transition-all duration-300 {{ request()->routeIs('itineraries.*') ? 'font-medium text-gray-900' : '' }}">
           My Trips
        </a>
        <a href="{{ route('preferences.select') }}" 
           class="text-lg text-gray-700 hover:text-gray-900 transition-all duration-300 {{ request()->routeIs('preferences.*') ? 'font-medium text-gray-900' : '' }}">
           Preferences
        </a>
    </div>

    <!-- Mobile Menu Button (Hidden on larger screens) -->
    <div class="md:hidden">
        <button id="mobile-menu-button" class="text-gray-700 hover:text-gray-900 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    <!-- Mobile Menu (Hidden by default) -->
    <div id="mobile-menu" class="hidden absolute top-16 left-0 right-0 bg-white shadow-lg rounded-b-lg z-40 md:hidden">
        <div class="px-4 py-3 space-y-3">
            <a href="{{ route('dashboard') }}" 
               class="block text-gray-700 hover:text-gray-900 {{ request()->routeIs('dashboard') ? 'font-medium' : '' }}">
               Home
            </a>
            <a href="{{ route('itineraries.index') }}" 
               class="block text-gray-700 hover:text-gray-900 {{ request()->routeIs('itineraries.*') ? 'font-medium' : '' }}">
               My Trips
            </a>
            <a href="{{ route('preferences.select') }}" 
               class="block text-gray-700 hover:text-gray-900 {{ request()->routeIs('preferences.*') ? 'font-medium' : '' }}">
               Preferences
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left text-gray-700 hover:text-gray-900">
                    Log Out
                </button>
            </form>
        </div>
    </div>

    <!-- Logout Button (Visible on larger screens) -->
    <div class="hidden md:flex space-x-3">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="px-5 py-2 bg-pink-900 text-white text-base font-medium rounded-lg transition-all duration-300 hover:bg-gray-800">
                Log Out
            </button>
        </form>
    </div>
</nav>

<script>
    // Toggle profile dropdown
    function toggleDropdown(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('profile-dropdown');
        dropdown.classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('profile-dropdown');
        if (!event.target.closest('.logo-button') && !event.target.closest('#profile-dropdown')) {
            dropdown.classList.add('hidden');
        }
    });

    // Mobile menu functionality
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    mobileMenuButton.addEventListener('click', function() {
        mobileMenu.classList.toggle('hidden');
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('#mobile-menu-button') && !event.target.closest('#mobile-menu')) {
            mobileMenu.classList.add('hidden');
        }
    });
</script>