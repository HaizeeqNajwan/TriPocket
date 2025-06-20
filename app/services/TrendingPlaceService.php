<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class TrendingPlaceService
{
    public static function fetch($destination)
    {
        $response = Http::get("https://en.wikivoyage.org/api/rest_v1/page/summary/" . urlencode($destination));

        if ($response->successful()) {
            $summary = $response->json('extract');
            preg_match_all('/\b[A-Z][a-z]+(?:\s[A-Z][a-z]+)*\b/', $summary, $matches);
            return array_slice(array_unique($matches[0]), 0, 5); // grab top 5 places
        }

        return [];
    }
}
