@extends('layouts.auth')

@section('title', 'Select Preferences')

@section('content')
<div class="relative min-h-screen flex flex-col items-center text-center px-4 md:px-6 mt-26">
    <!-- Background -->
    <div class="fixed inset-0 bg-cover bg-center opacity-30 -z-10" style="background-image: url('{{ asset('images/Background.png') }}'); background-attachment: fixed;"></div>

    <div class="relative w-full max-w-md animate-fade-in mt-36">
        <div class="bg-white/80 backdrop-blur-md rounded-lg shadow-lg p-8 max-w-sm mx-auto">
            @php
                $prefs = $savedPreferences ?? [];
            @endphp

            @if (!empty($prefs) && empty($editMode))
                <!-- Enhanced Success Card -->
                <div class="flex flex-col items-center text-center space-y-4 pt-6 pb-4">
                    <!-- Green Icon Badge -->
                    <div class="bg-green-500 text-white rounded-full p-4 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <h1 class="text-2xl font-bold text-gray-800">Preferences Selected</h1>
                    <p class="text-gray-600">Want to add or change them?</p>
                    <a href="{{ route('preferences.edit') }}" class="bg-pink-700 text-white px-6 py-2 rounded hover:bg-pink-900 transition shadow">
                        Edit Preferences
                    </a>
                </div>
            @else
                <!-- Selection Form -->
                <h1 class="text-2xl font-bold mb-6">
                    <span class="text-pink-600">What's Your</span>
                    <span class="text-black"> Interests</span>
                </h1>

                <form action="{{ route('preferences.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <!-- Nature Section -->
                        <div class="mb-6">
                            <h2 class="text-left font-medium text-gray-700 mb-3">Nature</h2>
                            <div class="grid grid-cols-2 gap-3">
                                <x-pref-card name="hiking" image="Hiking.jpg" label="Hiking" :checked="in_array('hiking', $prefs)" />
                                <x-pref-card name="camping" image="Camping.jpg" label="Camping" :checked="in_array('camping', $prefs)" />
                                <x-pref-card name="photography" image="Photography.jpg" label="Photography" :checked="in_array('photography', $prefs)" />
                            </div>
                        </div>

                        <!-- Urban Section -->
                        <div class="mb-6">
                            <h2 class="text-left font-medium text-gray-700 mb-3">Urban</h2>
                            <div class="grid grid-cols-2 gap-3">
                                <x-pref-card name="shopping" image="Shopping.jpeg" label="Shopping" :checked="in_array('shopping', $prefs)" />
                                <x-pref-card name="culture" image="Culture.jpg" label="Culture" :checked="in_array('culture', $prefs)" />
                                <x-pref-card name="historical" image="Historical.jpg" label="Historical" :checked="in_array('historical', $prefs)" />
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-gray-200 my-6"></div>

                        <!-- Other Section -->
                        <div class="mb-6">
                            <h2 class="text-left font-medium text-gray-700 mb-3">Other</h2>
                            <div class="flex mb-3">
                                <input type="text" name="custom_preference" 
                                    class="flex-1 rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Add your own interest" id="customPrefInput">
                                <button type="button" onclick="addCustomPreference()" 
                                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300 transition">
                                    Add
                                </button>
                            </div>
                            <div id="custom-preferences-container" class="grid grid-cols-2 gap-3">
                                @foreach($prefs as $pref)
                                    @if (!in_array($pref, ['hiking', 'camping', 'photography', 'shopping', 'culture', 'historical']))
                                        <label class="relative overflow-hidden rounded-lg border border-gray-200 hover:border-blue-400 transition-all cursor-pointer">
                                            <div class="bg-gradient-to-r from-blue-400 to-purple-500 w-full h-24"></div>
                                            <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                                                <input type="checkbox" name="preferences[]" value="{{ $pref }}" checked
                                                    class="absolute top-2 left-2 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                                <span class="text-white font-medium drop-shadow-md">{{ $pref }}</span>
                                            </div>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- Hidden field for JS custom preferences -->
                        <input type="hidden" name="custom_preferences" id="custom-preferences-data">

                        <!-- Submit Button -->
                        <div class="mt-8">
                            <button type="submit"
                                    class="w-full px-6 py-3 bg-pink-900 text-white rounded-md hover:bg-black-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

<!-- Animation -->
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
</style>

<!-- Script for checkbox limit and custom preference -->
<script>
    function addCustomPreference() {
        const input = document.getElementById('customPrefInput');
        const value = input.value.trim();
        const container = document.getElementById('custom-preferences-container');

        if (!value) return;

        const totalSelected = document.querySelectorAll('input[type="checkbox"][name="preferences[]"]:checked').length;

        if (totalSelected >= 5) {
            alert("You can select up to 5 preferences only.");
            return;
        }

        const label = document.createElement('label');
        label.className = 'relative overflow-hidden rounded-lg border border-gray-200 hover:border-blue-400 transition-all cursor-pointer';
        label.innerHTML = `
            <div class="bg-gradient-to-r from-blue-400 to-purple-500 w-full h-24"></div>
            <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                <input type="checkbox" name="preferences[]" value="${value}" checked
                    class="absolute top-2 left-2 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                <span class="text-white font-medium drop-shadow-md">${value}</span>
            </div>
        `;

        container.appendChild(label);
        input.value = '';
        updateCheckboxLimit();
    }

    function updateCheckboxLimit() {
        const allCheckboxes = document.querySelectorAll('input[type="checkbox"][name="preferences[]"]');
        const checked = Array.from(allCheckboxes).filter(cb => cb.checked);
        const limit = 5;

        allCheckboxes.forEach(cb => {
            cb.disabled = !cb.checked && checked.length >= limit;
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.addEventListener('change', updateCheckboxLimit);
        updateCheckboxLimit();
    });
</script>
@endsection
