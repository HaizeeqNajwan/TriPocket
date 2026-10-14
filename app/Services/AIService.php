<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class AIService
{
    public function generateItinerary($prompt)
    {
        try {
            $response = Http::timeout(120)->post('http://host.docker.internal:1234/v1/chat/completions', [
                'model' => 'qwen3.5-9b-deepseek-v4-flash',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful travel assistant. Respond with the itinerary directly, no thinking tags.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
                'stream' => false,
            ]);
            Log::info('AI raw status', ['status' => $response->status()]);
            Log::info('AI raw body', ['body' => $response->body()]);
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
            Log::error('AIService exception', ['message' => $e->getMessage()]);
        }
        return null;
    }
}
