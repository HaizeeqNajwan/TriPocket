<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Services\AIService;
use App\Services\GeocoderService;
use App\Models\SavedItinerary;
use Barryvdh\DomPDF\Facade\Pdf;

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
            'travel_type' => 'required|string|in:cultural,adventure,relaxation,business,food,romantic,solo,family',
        ]);
    
        $userPreferences = Auth::check() ? Auth::user()->preferences ?? [] : [];
        $customPreferences = Auth::check() ? json_decode(Auth::user()->custom_preferences ?? '[]', true) : [];
    
        $prefs = array_merge($userPreferences, $customPreferences);
    
        $preferenceString = '';
        if (!empty($prefs)) {
            $preferenceString .= "Tailor the itinerary around the following user preferences: ";
            $preferenceString .= implode(', ', $prefs) . ".\n";
            $preferenceString .= "Ensure that activities align with these interests.\n";
            $preferenceString .= "Do not include generic or irrelevant activities.";
        }
    
        $prompt = <<<EOT
    Generate a {$validated['days']}-day {$validated['travel_type']} travel itinerary for a trip to {$validated['destination']}.
    
    {$preferenceString}
    
    Use this format:
    
    Day 1:
    08:00 Activity Name @ Location - Short description
    10:00 Activity Name @ Location - Short description
    ...
    (3–5 activities total)
    
    Day 2:
    08:00 Activity Name @ Location - Short description
    ...
    
    Continue this format up to Day {$validated['days']}.
    Do not skip any days.
    Be concise and use clear, simple language.
    EOT;
    
        $cacheKey = 'itinerary_' . md5($prompt);
        $response = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($prompt) {
            return (new AIService())->generateItinerary($prompt);
        });
    
        Log::info('AI Prompt:', ['prompt' => $prompt]);
        Log::info('AI Response:', ['response' => $response]);
    
        if (!$response) {
            Log::error('AI Service returned no response');
            return redirect()->route('dashboard')->with('error', 'AI failed to generate an itinerary. Please try again later.');
        }
    
        $itinerary = $this->parseResponse($response);
    
        if (empty($itinerary['schedule'])) {
            Log::error('Failed to parse itinerary response', ['response' => $response]);
            return redirect()->route('dashboard')->with('error', 'Unable to understand the itinerary format. Please regenerate.');
        }
    
        return view('generate', [
            'itinerary' => $itinerary['schedule'],
            'places' => $this->getMapLocations($itinerary['schedule']),
            'tripLocation' => $validated['destination'],
            'tripTheme' => $validated['travel_type']
        ]);
    }
    

    protected function parseResponse($response)
    {
        $lines = preg_split("/\r\n|\n|\r/", $response);
        $schedule = [];
        $currentDay = '';
        $activities = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if (preg_match('/^Day\s*(\d+)/i', $line, $dayMatch)) {
                if ($currentDay && !empty($activities)) {
                    $schedule[] = [
                        'day' => $currentDay,
                        'activities' => $activities
                    ];
                    $activities = [];
                }
                $currentDay = 'Day ' . $dayMatch[1];
            } elseif (preg_match('/^(\d{1,2}:\d{2})\s+(.*?)\s+@\s+(.*?)\s+-\s+(.*)$/', $line, $match)) {
                $activities[] = [
                    'time' => trim($match[1]),
                    'name' => trim($match[2]),
                    'location' => trim($match[3]),
                    'details' => Str::limit(trim($match[4]), 180)
                ];
            }
        }

        if ($currentDay && !empty($activities)) {
            $schedule[] = [
                'day' => $currentDay,
                'activities' => $activities
            ];
        }

        return [
            'schedule' => $schedule,
            'raw' => $response
        ];
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
            'days.*.activities.*.location' => 'nullable|string',
            'days.*.activities.*.details' => 'nullable|string',
        ]);

        session(['customize_data' => $validated['days']]);

        return response()->json([
            'success' => true,
            'redirect' => route('itinerary.edit')
        ]);
    }

    public function edit()
    {
        $itinerary = session('customize_data');

        if (!$itinerary) {
            return redirect()->route('dashboard')->with('error', 'No itinerary found to customize.');
        }

        return view('itineraries.customize', compact('itinerary'));
    }

    public function storeFinal(Request $request)
    {
        $validated = $request->validate([
            'days' => 'required|array',
            'days.*.day' => 'required|string',
            'days.*.activities' => 'required|array',
            'days.*.activities.*.time' => 'required|string',
            'days.*.activities.*.name' => 'required|string',
            'days.*.activities.*.location' => 'nullable|string',
            'days.*.activities.*.details' => 'nullable|string',
        ]);

        $fallbackLocation = 'Custom Trip';

        $saved = SavedItinerary::create([
            'user_id' => auth()->id(),
            'title' => 'Customized Itinerary',
            'location' => $request->input('location', $fallbackLocation),
            'theme' => $request->input('theme', 'custom'),
            'days' => json_encode($validated['days']),
        ]);

        return redirect()->route('itinerary.view', $saved->id)
                         ->with('success', 'Custom itinerary saved!');
    }

    public function downloadPdf($id)
    {
        $itinerary = SavedItinerary::findOrFail($id);
        $pdf = Pdf::loadView('itineraries.pdf', compact('itinerary'));

        return $pdf->stream($itinerary->title . '_itinerary.pdf');
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

        return redirect()->back()->with('success', 'Itinerary deleted successfully.');
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
