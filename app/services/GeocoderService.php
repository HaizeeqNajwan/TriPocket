<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeocoderService
{
    public function getCoordinates($location)
    {
        $response = Http::withHeaders([
            'User-Agent' => 'Tripocket-FYP/1.0 (haizeeq.najwan@student.edu.my)',
        ])
        ->timeout(10)
        ->get('https://nominatim.openstreetmap.org/search', [
            'q' => $location,
            'format' => 'json',
            'limit' => 1,
        ]);

        if ($response->successful() && isset($response[0])) {
            return [
                'lat' => (float) $response[0]['lat'],
                'lng' => (float) $response[0]['lon'],
            ];
        }

        return ['lat' => 0, 'lng' => 0];
    }
}
