<nav class="fixed top-0 left-0 w-full px-6 md:px-10 py-4 bg-white/10 backdrop-blur-lg shadow-md z-50 flex items-center justify-between rounded-b-xl">
    <!-- Logo Button -->
    <div class="flex items-center space-x-2 md:space-x-4 relative z-50">
        <button onclick="toggleDropdown(event)" class="logo-button text-xl md:text-2xl font-semibold tracking-wide focus:outline-none">
            <span class="text-pink-900">TriP</span><span class="text-stone-600">ocket</span>
        </button>
    </div>

    <!-- Center Links -->
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

    <!-- Logout Desktop -->
    <div class="hidden md:flex space-x-3">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="px-5 py-2 bg-pink-900 text-white text-base font-medium rounded-lg hover:bg-gray-800 transition">
                Log Out
            </button>
        </form>
    </div>

    <!-- Mobile Menu Button -->
    <div class="md:hidden">
        <button id="mobile-menu-button" class="text-gray-700 hover:text-gray-900 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>
</nav>

<!-- ✅ Enhanced Profile Dropdown -->
<div id="profile-dropdown"
     class="invisible opacity-0 scale-95 transition-all duration-300 ease-out absolute top-24 left-1/2 transform -translate-x-1/2 w-72 bg-white rounded-xl shadow-2xl z-40 border border-gray-200">

    <!-- Profile Info -->
    <div class="px-6 py-5 text-center border-b border-gray-200 bg-white rounded-t-xl">
        <img src="{{ Auth::user()->profile_photo_url }}"
             alt="Profile Photo"
             class="w-16 h-16 rounded-full mx-auto mb-3 shadow-sm border border-gray-300">
        <p class="text-base font-semibold text-gray-800 leading-tight">{{ Auth::user()->name }}</p>
        <p class="text-xs text-gray-500 mt-0.5">My Profile</p>
    </div>

    <!-- Preferences -->
    <div class="px-6 py-4 bg-gray-50 rounded-b-xl">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Preferences</span>
            <a href="{{ route('profile.edit') }}" class="text-xs text-blue-600 hover:underline transition">Edit</a>
        </div>

        @php $preferences = auth()->user()->preferences ?? []; @endphp
        <div class="flex flex-wrap gap-2">
            @if(is_array($preferences) && !empty($preferences))
                @foreach($preferences as $preference)
                    <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full text-xs font-medium shadow-sm">
                        {{ ucfirst($preference) }}
                    </span>
                @endforeach
            @else
                <p class="text-sm text-gray-400">No preferences selected.</p>
            @endif
        </div>

        <!-- Optional Logout Inside Dropdown -->
        <form action="{{ route('logout') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit"
                    class="w-full text-sm text-gray-600 hover:text-red-600 border-t border-gray-200 pt-3 text-center transition">
                Log Out
            </button>
        </form>
    </div>
</div>

<!-- ✅ Mobile Menu -->
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

<!-- ✅ Scripts -->
<script>
    function toggleDropdown(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('profile-dropdown');
        const isVisible = dropdown.classList.contains('visible');

        if (!isVisible) {
            dropdown.classList.remove('invisible', 'opacity-0', 'scale-95');
            dropdown.classList.add('visible', 'opacity-100', 'scale-100');
        } else {
            dropdown.classList.add('invisible', 'opacity-0', 'scale-95');
            dropdown.classList.remove('visible', 'opacity-100', 'scale-100');
        }
    }

    document.addEventListener('click', function (event) {
        const dropdown = document.getElementById('profile-dropdown');
        if (!event.target.closest('.logo-button') && !event.target.closest('#profile-dropdown')) {
            dropdown.classList.add('invisible', 'opacity-0', 'scale-95');
            dropdown.classList.remove('visible', 'opacity-100', 'scale-100');
        }
    });

    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenuButton.addEventListener('click', function (event) {
        event.stopPropagation();
        mobileMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', function (event) {
        if (!event.target.closest('#mobile-menu-button') && !event.target.closest('#mobile-menu')) {
            mobileMenu.classList.add('hidden');
        }
    });
</script>
