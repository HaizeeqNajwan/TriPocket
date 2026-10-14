<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeocoderService
{
    public function getCoordinates($location)
    {
        $response = Http::get("https://nominatim.openstreetmap.org/search", [
            'q' => $location,
            'format' => 'json',
            'limit' => 1
        ]);

        if ($response->successful() && isset($response[0])) {
            return [
                'lat' => $response[0]['lat'],
                'lng' => $response[0]['lon']
            ];
        }

        return ['lat' => 0, 'lng' => 0];
    }
}
