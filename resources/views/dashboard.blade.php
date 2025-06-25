@extends('layouts.auth')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TriPocket - Homepage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }
        
        /* New loading animation styles */
        @keyframes planeFlight {
            0% { transform: translateX(-100%) rotate(0deg); }
            30% { transform: translateX(30%) rotate(5deg); }
            50% { transform: translateX(50%) rotate(0deg); }
            70% { transform: translateX(70%) rotate(-5deg); }
            100% { transform: translateX(200%) rotate(0deg); }
        }
        
        @keyframes pulseGrow {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        
        @keyframes customSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .plane-animation {
            animation: planeFlight 3s ease-in-out infinite;
        }
        
        .pulse-animation {
            animation: pulseGrow 2s ease-in-out infinite;
        }
        
        .float-animation {
            animation: float 4s ease-in-out infinite;
        }
        
        .custom-spin {
            animation: customSpin 1.5s linear infinite;
        }
        
        .gradient-text {
            background: linear-gradient(90deg, #EC4899, #8B5CF6);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
    </style>
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
</head>
<body class="bg-black/20 text-gray-900 backdrop-blur-md font-[Inter]">

    <!-- Enhanced Loading Spinner -->
    <div id="loading-spinner" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex flex-col items-center justify-center z-50">
        <div class="relative w-full max-w-md h-64 mb-8 overflow-hidden">
            <!-- Floating islands background -->
            <div class="absolute bottom-0 left-0 w-full h-1/2 flex justify-between px-8">
                <div class="w-16 h-16 bg-green-500 rounded-full float-animation" style="animation-delay: 0s;"></div>
                <div class="w-20 h-20 bg-blue-500 rounded-full float-animation" style="animation-delay: 0.5s;"></div>
                <div class="w-12 h-12 bg-purple-500 rounded-full float-animation" style="animation-delay: 1s;"></div>
            </div>
            
            <!-- Animated plane -->
            <svg class="plane-animation absolute top-1/2 left-0 w-16 h-16 text-pink-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
            </svg>
            
            <!-- Pulsing suitcase -->
            <div class="pulse-animation absolute bottom-8 right-1/4 transform translate-x-1/2">
                <svg class="w-12 h-12 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
        </div>
        
        <!-- Custom spinner with travel icons -->
        <div class="relative w-24 h-24 mb-6">
            <div class="absolute inset-0 border-4 border-t-pink-600 border-r-purple-600 border-b-blue-600 border-l-yellow-500 rounded-full custom-spin"></div>
            <div class="absolute inset-4 border-4 border-t-transparent border-r-transparent border-b-transparent border-l-gray-300 rounded-full custom-spin" style="animation-direction: reverse;"></div>
            
            <svg class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-8 h-8 text-pink-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
        
        <!-- Animated loading text -->
        <div class="text-center">
            <h2 class="text-2xl font-bold gradient-text mb-2 animate__animated animate__pulse animate__infinite">Crafting Your Journey</h2>
            <p class="text-white/80 mb-4">Discovering the best experiences in <span id="loading-destination" class="font-semibold text-pink-300">Malaysia</span></p>
            
            <!-- Progress dots -->
            <div class="flex justify-center space-x-2">
                <div class="w-2 h-2 bg-pink-400 rounded-full animate-bounce" style="animation-delay: 0.1s;"></div>
                <div class="w-2 h-2 bg-purple-400 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.3s;"></div>
            </div>
            
            <!-- Fun travel tip -->
            <p class="text-white/60 text-sm mt-6 italic" id="travel-tip">Did you know? Malaysia has over 800 islands!</p>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="relative h-screen flex flex-col items-center justify-center text-center px-0">
        <div class="absolute inset-0 bg-cover bg-center opacity-30" style="background-image: url('{{ asset('images/Background.png') }}'); z-index: -1;"></div>

        @if (auth()->user()->preferences_selected)
            <h1 class="text-3xl md:text-5xl font-semibold text-pink-700">
                Plan Your <span class="text-gray-900">Perfect Trip</span>
            </h1>
            <h1 class="mt-4 text-lg text-gray-700">We will make sure you enjoy your time in Malaysia!</h1>

            <!-- Form Box -->
            <div class="bg-white/70 backdrop-blur-md rounded-lg shadow-lg p-6 w-full max-w-3xl mt-6">
                <form action="{{ route('itinerary.generate') }}" method="" class="grid grid-cols-1 md:grid-cols-3 gap-4" id="itinerary-form">
                    @csrf 
                    <!-- Destination -->
                    <div class="flex flex-col space-y-2">
                        <label for="destination" class="text-sm text-gray-700 font-medium">Where to?</label>
                        <select id="destination" name="destination" class="appearance-none w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-stone-600 bg-white/70 backdrop-blur-md text-gray-700 transition-all duration-300 hover:bg-white/80 cursor-pointer" required>
                            <option value="" disabled selected>Select a state</option>
                            <option value="Johor">Johor</option>
                            <option value="Kedah">Kedah</option>
                            <option value="Kelantan">Kelantan</option>
                            <option value="Malacca">Malacca</option>
                            <option value="Negeri Sembilan">Negeri Sembilan</option>
                            <option value="Pahang">Pahang</option>
                            <option value="Penang">Penang</option>
                            <option value="Perak">Perak</option>
                            <option value="Perlis">Perlis</option>
                            <option value="Sabah">Sabah</option>
                            <option value="Sarawak">Sarawak</option>
                            <option value="Selangor">Selangor</option>
                            <option value="Terengganu">Terengganu</option>
                            <option value="Kuala Lumpur">Kuala Lumpur</option>
                            <option value="Labuan">Labuan</option>
                            <option value="Putrajaya">Putrajaya</option>
                        </select>
                    </div>

                    <!-- Days -->
                    <div class="flex flex-col space-y-2">
                        <label for="days" class="text-sm text-gray-700 font-medium">Days?</label>
                        <select id="days" name="days" class="appearance-none w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-stone-600 bg-white/70 backdrop-blur-md text-gray-700 transition-all duration-300 hover:bg-white/80 cursor-pointer" required>
                            <option value="" disabled selected>Select days</option>
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">{{ $i }} day{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Travel Type -->
                    <div class="flex flex-col space-y-2">
                        <label for="travel_type" class="text-sm text-gray-700 font-medium">Travel Types</label>
                        <select id="travel_type" name="travel_type" class="appearance-none w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-stone-600 bg-white/70 backdrop-blur-md text-gray-700 transition-all duration-300 hover:bg-white/80 cursor-pointer" required>
                            <option value="" disabled selected>Select travel type</option>
                            <option value="adventure">Adventure</option>
                            <option value="relaxation">Relaxation</option>
                            <option value="cultural">Cultural</option>
                            <option value="food">Food</option>
                            <option value="family">Family</option>
                            <option value="romantic">Romantic</option>
                            <option value="solo">Solo</option>
                            <option value="business">Business</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="md:col-span-3 flex justify-center">
                        <button type="submit" class="px-8 py-3 bg-pink-700 text-white text-lg font-medium rounded-lg transition-all duration-300 hover:bg-stone-700 w-full md:w-auto">
                            Generate Itinerary
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <script>
        const form = document.getElementById('itinerary-form');
        const spinner = document.getElementById('loading-spinner');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            spinner.classList.remove('hidden');
            
            // Update loading message with selected destination
            const destination = document.getElementById('destination').value || 'Malaysia';
            document.getElementById('loading-destination').textContent = destination;
            
            // Show random travel tip
            const travelTips = [
                "Did you know? Malaysia has over 800 islands!",
                "Pro tip: Try local street food for authentic flavors",
                "Malaysia has the world's largest roundabout in Putrajaya",
                "The Rafflesia flower in Malaysia can grow up to 1m wide!",
                "Malaysia has beaches, mountains, and rainforests all in one country"
            ];
            document.getElementById('travel-tip').textContent = 
                travelTips[Math.floor(Math.random() * travelTips.length)];
            
            // Submit the form after showing animations
            setTimeout(() => {
                form.submit();
            }, 200);
        });
    </script>
</body>
</html>