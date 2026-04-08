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
                'HTTP-Referer' => config('app.url'),
                'X-Title' => 'TriPocket AI',
                'Content-Type' => 'application/json',
            ])
            ->timeout(60)
            ->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'meta-llama/llama-3.2-3b-instruct:free',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful travel assistant that generates structured travel itineraries.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ],
                ],
            ]);

            if ($response->successful()) {
                $message = $response->json('choices.0.message.content');

                Log::info('AI response received', ['content' => $message]);

                return $message;
            }

            Log::error('AIService error response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Exception $e) {
            Log::error('AIService exception', [
                'message' => $e->getMessage(),
            ]);
        }

        return null;
    }
}
