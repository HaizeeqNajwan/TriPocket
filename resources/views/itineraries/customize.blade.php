@extends('layouts.auth')

@section('title', 'Customize Itinerary')

@section('content')
<div class="relative min-h-screen flex flex-col items-center justify-center px-4 md:px-6 pt-16">
    <!-- Background -->
    <div class="fixed inset-0 bg-cover bg-center opacity-30 -z-10" 
         style="background-image: url('{{ asset('images/Background.png') }}'); background-attachment: fixed;">
    </div>

    <!-- Main Content Card -->
    <div class="w-full max-w-6xl bg-white rounded-lg shadow-lg p-6 mb-10 overflow-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">🛠️ Customize Your Itinerary</h1>

        <form method="POST" action="{{ route('itinerary.saveFinal') }}" id="itineraryForm">
            @csrf

            <div id="daysWrapper">
                @foreach ($itinerary as $dayIndex => $day)
                    <div class="mb-10 border-l-4 border-pink-500 pl-4 day-block" data-day-index="{{ $dayIndex }}">
                        <h2 class="text-xl font-semibold text-pink-600 mb-4">
                            Day {{ $dayIndex + 1 }}: 
                            <input type="text" name="days[{{ $dayIndex }}][day]" value="{{ $day['day'] }}"
                                class="ml-2 border border-gray-300 rounded px-2 py-1 text-gray-800 w-1/2" required>
                        </h2>

                        <div class="activity-wrapper space-y-4">
                            @foreach ($day['activities'] as $activityIndex => $activity)
                                <div class="p-4 bg-gray-100 rounded-lg activity-block relative">
                                    <div class="grid md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Time</label>
                                            <input type="text" name="days[{{ $dayIndex }}][activities][{{ $activityIndex }}][time]"
                                                value="{{ $activity['time'] }}"
                                                class="w-full border rounded px-3 py-2" required>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium mb-1">Activity Name</label>
                                            <input type="text" name="days[{{ $dayIndex }}][activities][{{ $activityIndex }}][name]"
                                                value="{{ $activity['name'] }}"
                                                class="w-full border rounded px-3 py-2" required>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium mb-1">Location</label>
                                            <input type="text" name="days[{{ $dayIndex }}][activities][{{ $activityIndex }}][location]"
                                                value="{{ $activity['location'] ?? '' }}"
                                                class="w-full border rounded px-3 py-2">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium mb-1">Details</label>
                                            <textarea name="days[{{ $dayIndex }}][activities][{{ $activityIndex }}][details]"
                                                rows="2" class="w-full border rounded px-3 py-2">{{ $activity['details'] ?? '' }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Remove Activity Button -->
                                    <button type="button"
                                            class="absolute top-2 right-2 text-red-500 hover:text-red-700 text-sm remove-activity">
                                        ✖
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <!-- Add Activity Button -->
                        <button type="button"
                                class="mt-4 bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm px-3 py-2 rounded add-activity"
                                data-day-index="{{ $dayIndex }}">
                            ➕ Add Activity
                        </button>
                    </div>
                @endforeach
            </div>

            <div class="mt-10 text-center">
                <button type="submit"
                        class="bg-pink-600 hover:bg-pink-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                    Save Custom Itinerary
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Add Activity Button
        document.querySelectorAll('.add-activity').forEach(button => {
            button.addEventListener('click', function () {
                const dayIndex = this.dataset.dayIndex;
                const dayBlock = this.closest('.day-block');
                const activitiesWrapper = dayBlock.querySelector('.activity-wrapper');
                const currentIndex = activitiesWrapper.querySelectorAll('.activity-block').length;

                const template = `
                <div class="p-4 bg-gray-100 rounded-lg activity-block relative mt-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Time</label>
                            <input type="text" name="days[${dayIndex}][activities][${currentIndex}][time]" 
                                class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Activity Name</label>
                            <input type="text" name="days[${dayIndex}][activities][${currentIndex}][name]" 
                                class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Location</label>
                            <input type="text" name="days[${dayIndex}][activities][${currentIndex}][location]" 
                                class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Details</label>
                            <textarea name="days[${dayIndex}][activities][${currentIndex}][details]" 
                                rows="2" class="w-full border rounded px-3 py-2"></textarea>
                        </div>
                    </div>
                    <button type="button" class="absolute top-2 right-2 text-red-500 hover:text-red-700 text-sm remove-activity">
                        ✖
                    </button>
                </div>`;
                activitiesWrapper.insertAdjacentHTML('beforeend', template);
                addRemoveFunctionality();
            });
        });

        function addRemoveFunctionality() {
            document.querySelectorAll('.remove-activity').forEach(button => {
                button.onclick = () => {
                    button.closest('.activity-block').remove();
                };
            });
        }

        addRemoveFunctionality();
    });
</script>
@endsection
