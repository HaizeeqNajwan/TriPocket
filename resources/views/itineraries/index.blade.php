@extends('layouts.auth')

@section('content')
<div class="relative min-h-screen flex flex-col items-center px-4 md:px-6 pt-12 pb-8">
    <!-- Background -->
    <div class="fixed inset-0 bg-cover bg-center opacity-30 -z-10"
         style="background-image: url('{{ asset('images/Background.png') }}'); background-attachment: fixed;">
    </div>

    <!-- Header -->
    <div class="w-full max-w-7xl text-center mb-8">
        <h1 class="text-4xl font-semibold">
            <span class="text-black">Your Saved</span>
            <span class="text-pink-600"> Itineraries</span>
        </h1>
    </div>

    <!-- Success message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- If no itineraries -->
    @if($itineraries->isEmpty())
        <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md text-center mx-auto mt-8">
            <p class="text-gray-500 text-base mb-4">You haven't saved any itineraries yet.</p>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-pink-600 hover:text-pink-800 transition font-medium">
                <i class="fas fa-arrow-left"></i> Back to Planner
            </a>
        </div>
    @else
        <!-- Grid of itineraries -->
        <div class="w-full max-w-7xl mx-auto px-4 mt-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($itineraries as $itinerary)
                    @php
                        $days = is_string($itinerary->days) ? json_decode($itinerary->days, true) : $itinerary->days;
                        $dayCount = is_array($days) ? count($days) : 0;
                    @endphp

                    <div class="bg-white rounded-2xl shadow hover:shadow-xl transition-shadow duration-300 overflow-hidden h-full flex flex-col">
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex-grow">
                                <div class="flex justify-between items-start mb-2">
                                    <h2 class="text-lg md:text-xl font-semibold text-gray-800 line-clamp-2">{{ $itinerary->title }}</h2>
                                    <span class="bg-pink-100 text-pink-700 text-xs px-3 py-1 rounded-full whitespace-nowrap">
                                        {{ $dayCount }} {{ Str::plural('day', $dayCount) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500 mb-3">
                                    {{ $itinerary->location }} • {{ ucfirst($itinerary->theme) }}
                                </p>
                            </div>

                            <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                                <span class="text-xs text-gray-400">
                                    {{ $itinerary->created_at->format('M d, Y') }}
                                </span>
                                <div class="flex items-center gap-4">
                                    <!-- Delete -->
                                    <form action="{{ route('saved-itinerary.delete', ['id' => $itinerary->id]) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this itinerary?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-500 hover:text-red-500 transition-colors duration-200 p-1">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>

                                    <!-- View -->
                                    <a href="{{ route('itinerary.view', ['id' => $itinerary->id]) }}"
                                       class="text-pink-600 hover:text-pink-800 font-medium text-sm flex items-center gap-1">
                                        View <i class="fas fa-chevron-right text-xs"></i>
                                    </a>

                                    <!-- PDF -->
                                    <a href="{{ route('saved-itinerary.pdf', ['id' => $itinerary->id]) }}"
                                       class="text-green-600 hover:text-green-800 font-medium text-sm flex items-center gap-1"
                                       target="_blank">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
