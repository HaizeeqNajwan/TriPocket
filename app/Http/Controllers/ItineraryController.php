<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Services\AIService;
use App\Services\GeocoderService;
use App\Models\SavedItinerary;

class ItineraryController extends Controller
{
    public function showDashboard()
    {
        return view('dashboard', [
            'itinerary' => session('itinerary', []),
            'error' => session('error', null)
        ]);
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'days' => 'required|integer|min:1|max:14',
            'destination' => 'required|string|max:255|regex:/^[\pL\s\-,]+$/u',
            'travel_type' => 'required|string|in:cultural,adventure,relaxation,business',
        ]);
        
        // Get user preferences - including custom ones
        $userPreferences = Auth::check() ? Auth::user()->preferences ?? [] : [];
        $customPreferences = Auth::check() ? json_decode(Auth::user()->custom_preferences ?? '[]', true) : [];
        
        // Format preferences for AI prompt
        $preferenceString = '';
        if (!empty($userPreferences) || !empty($customPreferences)) {
            $preferenceString = 'The user prefers activities like: ' . implode(', ', $userPreferences);
            
            if (!empty($customPreferences)) {
                $preferenceString .= ' (Also interested in: ' . implode(', ', $customPreferences) . ')';
            }
            $preferenceString .= ".\n";
        }
        
        $prompt = "Create a {$validated['days']}-day {$validated['travel_type']} itinerary for {$validated['destination']}.\n"
            . $preferenceString
            . "Use this format for each day:\n\nDay X:\nTime: ...\nActivity: ...\nLocation: ...\nDetails: ...\n\n"
            . "Only include exactly {$validated['days']} days. Use realistic timings and activities.";
        
        $response = (new AIService())->generateItinerary($prompt);

        if (!$response) {
            Log::error('AI Service failed to generate itinerary', ['input' => $validated]);
            return redirect()->route('dashboard')->with('error', 'Failed to generate itinerary');
        }

        $itinerary = $this->parseResponse($response);

        if (empty($itinerary['schedule'])) {
            Log::error('Failed to parse itinerary response', ['response' => $response]);
            return redirect()->route('dashboard')->with('error', 'Failed to parse itinerary response');
        }

        return view('generate', [
            'itinerary' => $itinerary['schedule'],
            'places' => $this->getMapLocations($itinerary['schedule']),
            'tripLocation' => $validated['destination'],
            'tripTheme' => $validated['travel_type']
        ]);
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'days' => 'required|array',
            'days.*.day' => 'required|string',
            'days.*.activities' => 'required|array',
            'days.*.activities.*.time' => 'required|string',
            'days.*.activities.*.name' => 'required|string',
            'days.*.activities.*.location' => 'sometimes|string',
            'days.*.activities.*.details' => 'sometimes|string',
            'location' => 'required|string',
            'theme' => 'required|string',
        ]);
    
        $itinerary = SavedItinerary::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'location' => $validated['location'],
            'theme' => $validated['theme'],
            'days' => $validated['days'],
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Itinerary saved successfully!',
            'id' => $itinerary->id
        ]);
    }

    public function customize(Request $request)
    {
        $validated = $request->validate([
            'days' => 'required|array',
            'days.*.day' => 'required|string',
            'days.*.activities' => 'required|array',
            'days.*.activities.*.time' => 'required|string',
            'days.*.activities.*.name' => 'required|string',
            'days.*.activities.*.location' => 'sometimes|string',
            'days.*.activities.*.details' => 'sometimes|string',
        ]);

        session(['customized_itinerary' => $validated['days']]);

        return response()->json([
            'success' => true,
            'message' => 'Itinerary customized successfully!',
            'redirect' => route('itinerary.show', ['id' => 1])
        ]);
    }

    public function index()
    {
        $itineraries = SavedItinerary::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('itineraries.index', compact('itineraries'));
    }

    public function show($id)
    {
        $itinerary = SavedItinerary::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        try {
            $schedule = $itinerary->days;
            if (empty($schedule)) {
                throw new \Exception('Empty itinerary data');
            }
        } catch (\Exception $e) {
            Log::error('Failed to parse itinerary days', ['id' => $id, 'error' => $e->getMessage()]);
            return redirect()->route('dashboard')->with('error', 'Invalid itinerary data');
        }

        return view('generate', [
            'itinerary' => $schedule,
            'places' => $this->getMapLocations($schedule),
            'tripLocation' => $itinerary->location,
            'tripTheme' => $itinerary->theme,
            'saved' => true
        ]);
    }

    public function destroy($id)
    {
        $itinerary = SavedItinerary::findOrFail($id);
        $itinerary->delete();

        return redirect()->route('saved-itineraries')
               ->with('success', 'Itinerary deleted successfully');
    }

    protected function parseResponse($response)
    {
        preg_match_all('/Day\s*(\d+):\s*((?:(?!Day\s*\d+:).)*)/is', $response, $matches, PREG_SET_ORDER);

        $schedule = [];

        foreach ($matches as $match) {
            $dayNumber = $match[1];
            $dayBlock = trim($match[2]);

            preg_match_all('/Time:\s*(.*?)\nActivity:\s*(.*?)(?:\nLocation:\s*(.*?))?(?:\nDetails:\s*(.*?))?(?=\nTime:|\n?$)/s', $dayBlock, $activitiesMatches, PREG_SET_ORDER);

            $activities = [];
            foreach ($activitiesMatches as $act) {
                $activities[] = [
                    'time' => trim($act[1]),
                    'name' => trim($act[2]),
                    'location' => isset($act[3]) ? trim($act[3]) : '',
                    'details' => isset($act[4]) ? trim($act[4]) : '',
                ];
            }

            if (!empty($activities)) {
                $schedule[] = [
                    'day' => 'Day ' . $dayNumber,
                    'activities' => $activities
                ];
            }
        }

        return [
            'schedule' => $schedule,
            'raw' => $response
        ];
    }

    protected function getMapLocations($schedule)
    {
        $locations = [];
        $seen = [];

        foreach ($schedule as $day) {
            foreach ($day['activities'] as $activity) {
                $loc = $activity['location'] ?? null;
                if ($loc && !in_array($loc, $seen)) {
                    $coords = Cache::remember("location_{$loc}", now()->addDay(), function () use ($loc) {
                        return (new GeocoderService())->getCoordinates($loc);
                    });

                    if ($coords) {
                        $locations[] = [
                            'name' => $loc,
                            'lat' => $coords['lat'] ?? null,
                            'lng' => $coords['lng'] ?? null
                        ];
                        $seen[] = $loc;
                    }
                }
            }
        }

        return $locations;
    }
}