@extends('layouts.auth')

@section('content')
<div class="relative min-h-screen flex flex-col items-center justify-center text-center px-4 md:px-6 pt-16">
    <!-- Background -->
    <div class="fixed inset-0 bg-cover bg-center opacity-30 -z-10" style="background-image: url('{{ asset('images/Background.png') }}'); background-attachment: fixed;"></div>

    <div class="flex flex-col md:flex-row justify-center gap-6 mt-10 w-full max-w-6xl">
        <!-- Locations List -->
        <div class="w-full md:w-1/2 bg-white rounded-lg shadow-lg p-6 overflow-auto max-h-[500px]">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Locations in Your Itinerary</h1>
            <div class="space-y-4">
                @if(!empty($itinerary) && is_array($itinerary))
                    @php
                        $uniqueLocations = [];
                        foreach($itinerary as $day) {
                            if(!empty($day['activities'])) {
                                foreach($day['activities'] as $activity) {
                                    if(!empty($activity['location'])) {
                                        $uniqueLocations[$activity['location']] = true;
                                    }
                                }
                            }
                        }
                    @endphp

                    @if(count($uniqueLocations) > 0)
                        @foreach(array_keys($uniqueLocations) as $location)
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($location . ' ' . ($tripLocation ?? '')) }}" 
                               target="_blank"
                               rel="noopener noreferrer"
                               class="block p-4 border rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                <h3 class="font-semibold text-gray-800">{{ $location }}</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    <i class="fas fa-map-marker-alt mr-1 text-red-500"></i>
                                    View on Google Maps
                                </p>
                            </a>
                        @endforeach
                    @else
                        <p class="text-gray-500 italic">No locations found in itinerary</p>
                    @endif
                @else
                    <p class="text-gray-500 italic">No itinerary data available</p>
                @endif
            </div>
        </div>

        <!-- Itinerary -->
        <div class="w-full md:w-1/2 bg-white rounded-lg shadow-lg p-6 overflow-auto max-h-[500px]">
            <h1 class="text-2xl font-bold text-gray-800 mb-1">Your Travel Itinerary</h1>
            <p class="text-gray-600 mb-4">Personalized for your {{ $tripTheme ?? 'adventure' }} trip</p>

            @if(!empty($itinerary) && is_array($itinerary))
                <!-- Trip Summary -->
                <div class="flex items-center gap-3 mb-6">
                    <span class="bg-pink-100 text-pink-800 text-sm font-medium px-3 py-1 rounded-full">
                        {{ count($itinerary) }} days
                    </span>
                    <span class="font-medium">{{ $tripLocation ?? 'Your Destination' }}</span>
                    <span class="text-gray-500">• {{ ucfirst($tripTheme ?? 'adventure') }}</span>
                </div>

                <!-- Flights Button -->
                <a href="https://www.google.com/travel/flights" target="_blank" rel="noopener noreferrer">
                    <button class="w-full bg-pink-500 hover:bg-pink-600 text-white font-medium py-2 px-4 rounded-lg mb-6 transition duration-200">
                        Find flights to {{ $tripLocation }}
                    </button>
                </a>

                <!-- Day-wise Activity Cards -->
                <div class="space-y-6">
                    @foreach ($itinerary as $day)
                        <div>
                            <h2 class="text-lg font-bold text-pink-600 mb-3">{{ $day['day'] }}</h2>
                            @if(!empty($day['activities']))
                                <div class="grid gap-4">
                                    @foreach ($day['activities'] as $activity)
                                        <div class="group">
                                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($activity['location'] . ' ' . ($tripLocation ?? '')) }}" 
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               class="block bg-white border-l-4 border-pink-400 shadow-md p-4 rounded-lg transition hover:scale-[1.01] duration-300 cursor-pointer">
                                                <h3 class="text-md font-semibold text-gray-800">{{ $activity['time'] }} - {{ $activity['name'] }}</h3>
                                                <p class="text-sm text-gray-500 mt-1 group-hover:text-pink-500 transition-colors">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                                    {{ $activity['location'] }}
                                                </p>
                                                <p class="text-gray-700 text-sm mt-2 whitespace-pre-line">{{ $activity['details'] }}</p>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-400 italic">No activities listed for this day.</p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between gap-4 mt-6">
                    <button id="customizeBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded flex-1 transition duration-200">
                        <i class="fas fa-edit mr-2"></i> Customize
                    </button>
                    <button id="savePlanBtn" class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded flex-1 transition duration-200"
                            data-itinerary="{{ json_encode($itinerary) }}"
                            data-location="{{ $tripLocation ?? '' }}"
                            data-theme="{{ $tripTheme ?? '' }}">
                        <i class="fas fa-save mr-2"></i> Save Plan
                    </button>
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-red-500 mb-4">No itinerary data available.</p>
                    <a href="{{ route('dashboard') }}" class="text-pink-600 hover:text-pink-800 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Back to planner
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Save Plan Button
        document.getElementById('savePlanBtn')?.addEventListener('click', async function() {
            const title = prompt("Enter a name for your itinerary:");
            if (!title) return;

            try {
                const response = await fetch("{{ route('itinerary.save') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        title: title,
                        days: JSON.parse(this.dataset.itinerary),
                        location: this.dataset.location,
                        theme: this.dataset.theme
                    })
                });

                const result = await response.json();
                if (result.success) {
                    alert('Itinerary saved successfully!');
                    if (result.id) {
                        window.location.href = "{{ route('itinerary.view', '') }}/" + result.id;
                    }
                } else {
                    throw new Error(result.message || 'Failed to save itinerary');
                }
            } catch (error) {
                console.error('Error:', error);
                alert(error.message);
            }
        });

        // Customize Button
        document.getElementById('customizeBtn')?.addEventListener('click', async function () {
            try {
                const response = await fetch("{{ route('itinerary.customize') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        days: @json($itinerary ?? [])
                    })
                });

                const result = await response.json();
                if (result.success && result.redirect) {
                    window.location.href = result.redirect;
                } else {
                    throw new Error(result.message || 'Failed to customize itinerary');
                }
            } catch (error) {
                console.error('Customize Error:', error);
                alert(error.message || 'Something went wrong');
            }
        });
    });
</script>
@endsection