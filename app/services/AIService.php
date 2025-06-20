<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    public function generateItinerary($prompt)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
                'HTTP-Referer' => 'https://tripocket.yourdomain.com/',
                'X-Title' => 'TriPocket AI',
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'mistralai/mixtral-8x7b-instruct',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful travel assistant.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            if ($response->successful()) {
                $message = $response->json('choices')[0]['message']['content'] ?? null;
                Log::info('AI response received', ['content' => $message]);
                return $message;
            } else {
                Log::error('AIService error response', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('AIService exception', [
                'message' => $e->getMessage()
            ]);
        }

        return null; // return null to trigger controller’s error handling
    }
}
