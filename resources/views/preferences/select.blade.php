@extends('layouts.auth')

@section('title', 'Select Preferences')

@section('content')
<div class="relative min-h-screen flex flex-col items-center justify-center text-center px-4 md:px-6 pt-16">
    <!-- Background -->
    <div class="fixed inset-0 bg-cover bg-center opacity-30 -z-10" style="background-image: url('{{ asset('images/Background.png') }}'); background-attachment: fixed;"></div>

    <div class="relative w-full max-w-md">
        <div class="bg-white/80 backdrop-blur-md rounded-lg shadow-lg p-8">
            <h1 class="text-2xl font-bold mb-6">
                <span class="text-pink-600">What's Your</span>
                <span class="text-black"> Interests</span>
            </h1>
            
            <form action="{{ route('preferences.store') }}" method="POST" id="preferences-form">
                @csrf
                <div class="space-y-6">
                    <!-- Nature Section -->
                    <div class="mb-6">
                        <h2 class="text-left font-medium text-gray-700 mb-3">Nature</h2>
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Hiking -->
                            <label class="relative overflow-hidden rounded-lg border border-gray-200 hover:border-blue-400 transition-all cursor-pointer">
                                <img src="{{ asset('images/Hiking.jpg') }}" alt="Hiking" class="w-full h-24 object-cover">
                                <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                                    <input type="checkbox" name="preferences[]" value="hiking" class="absolute top-2 left-2 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                    <span class="text-white font-medium drop-shadow-md">Hiking</span>
                                </div>
                            </label>
                            
                            <!-- Camping -->
                            <label class="relative overflow-hidden rounded-lg border border-gray-200 hover:border-blue-400 transition-all cursor-pointer">
                                <img src="{{ asset('images/Camping.jpg') }}" alt="Camping" class="w-full h-24 object-cover">
                                <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                                    <input type="checkbox" name="preferences[]" value="camping" class="absolute top-2 left-2 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                    <span class="text-white font-medium drop-shadow-md">Camping</span>
                                </div>
                            </label>
                            
                            <!-- Photography -->
                            <label class="relative overflow-hidden rounded-lg border border-gray-200 hover:border-blue-400 transition-all cursor-pointer">
                                <img src="{{ asset('images/Photography.jpg') }}" alt="Photography" class="w-full h-24 object-cover">
                                <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                                    <input type="checkbox" name="preferences[]" value="photography" class="absolute top-2 left-2 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                    <span class="text-white font-medium drop-shadow-md">Photography</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Urban Section -->
                    <div class="mb-6">
                        <h2 class="text-left font-medium text-gray-700 mb-3">Urban</h2>
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Shopping -->
                            <label class="relative overflow-hidden rounded-lg border border-gray-200 hover:border-blue-400 transition-all cursor-pointer">
                                <img src="{{ asset('images/Shopping.jpeg') }}" alt="Shopping" class="w-full h-24 object-cover">
                                <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                                    <input type="checkbox" name="preferences[]" value="shopping" class="absolute top-2 left-2 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                    <span class="text-white font-medium drop-shadow-md">Shopping</span>
                                </div>
                            </label>
                            
                            <!-- Culture -->
                            <label class="relative overflow-hidden rounded-lg border border-gray-200 hover:border-blue-400 transition-all cursor-pointer">
                                <img src="{{ asset('images/Culture.jpg') }}" alt="Culture" class="w-full h-24 object-cover">
                                <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                                    <input type="checkbox" name="preferences[]" value="culture" class="absolute top-2 left-2 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                    <span class="text-white font-medium drop-shadow-md">Culture</span>
                                </div>
                            </label>
                            
                            <!-- Historical -->
                            <label class="relative overflow-hidden rounded-lg border border-gray-200 hover:border-blue-400 transition-all cursor-pointer">
                                <img src="{{ asset('images/Historical.jpg') }}" alt="Historical" class="w-full h-24 object-cover">
                                <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                                    <input type="checkbox" name="preferences[]" value="historical" class="absolute top-2 left-2 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                    <span class="text-white font-medium drop-shadow-md">Historical</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 my-6"></div>

                    <!-- Other Section -->
                    <div class="mb-6">
                        <h2 class="text-left font-medium text-gray-700 mb-3">Other</h2>
                        <div class="flex mb-3">
                            <input type="text" id="custom-preference-input" 
                                   class="flex-1 rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Add your own interest">
                            <button type="button" onclick="addCustomPreference()" 
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300 transition">
                                Add
                            </button>
                        </div>
                        <div id="custom-preferences-container" class="grid grid-cols-2 gap-3">
                            <!-- Custom preferences will be added here -->
                        </div>
                    </div>

                    <!-- Hidden field to store custom preferences -->
                    <input type="hidden" name="custom_preferences" id="custom-preferences-data">

                    <!-- Submit Button -->
                    <div class="mt-8">
                        <button type="submit"
                                class="w-full px-6 py-3 bg-pink-900 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                            Next
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let customPreferences = [];

    function addCustomPreference() {
        const input = document.getElementById('custom-preference-input');
        const container = document.getElementById('custom-preferences-container');
        const value = input.value.trim();
        
        if (!value) return;
        
        // Add to our custom preferences array
        if (!customPreferences.includes(value)) {
            customPreferences.push(value);
            
            // Create new preference element with a placeholder image
            const label = document.createElement('label');
            label.className = 'relative overflow-hidden rounded-lg border border-gray-200 hover:border-blue-400 transition-all cursor-pointer';
            label.innerHTML = `
                <div class="bg-gradient-to-r from-blue-400 to-purple-500 w-full h-24"></div>
                <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                    <input type="checkbox" checked
                           class="absolute top-2 left-2 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <span class="text-white font-medium drop-shadow-md">${value}</span>
                </div>
            `;
            
            container.appendChild(label);
            input.value = '';
            
            // Update the hidden field
            document.getElementById('custom-preferences-data').value = JSON.stringify(customPreferences);
        }
    }

    // Before form submission, ensure custom preferences are included
    document.getElementById('preferences-form').addEventListener('submit', function(e) {
        // No need to prevent default, we're just updating the hidden field
        document.getElementById('custom-preferences-data').value = JSON.stringify(customPreferences);
    });
</script>
@endsection