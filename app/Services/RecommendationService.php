<?php

namespace App\Services;

use App\Models\Plat;
use App\Models\User;
use Illuminate\Support\Facades\Http;
class RecommendationService
{
    /**
     * Create a new class instance.
     */
    public function analyze(User $user, Plat $plat)
    {
        // 1. prepare the data to send to AI
        $userTags    = implode(', ', $user->dietary_tags ?? []);
        $ingredients = $plat->ingredients->pluck('name')->implode(', ');

        // 2. build the prompt
        $prompt = "
            You are a dietary compatibility analyzer.

            User dietary preferences: {$userTags}
            Plate name: {$plat->name}
            Plate ingredients: {$ingredients}

            IMPORTANT: Analyze compatibility and ALWAYS include relevant warnings for the user.
            
            Generate warnings for:
            - Allergens (nuts, dairy, gluten, etc.)
            - Dietary conflicts (meat for vegetarians, etc.)
            - Health concerns (high sodium, sugar, etc.)
            - Any ingredient that might conflict with user preferences
            
            Even if compatible, include helpful warnings if any ingredients could be concerning.

            Respond ONLY with this exact JSON format:
            {
                \"score\": 85,
                \"compatible\": true,
                \"reasoning\": \"Brief explanation of compatibility\",
                \"warnings\": [\"User-friendly warning message\", \"Another warning if needed\"]
            }
        ";

        // 3. send to Groq API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.groq.key'),
            'Content-Type'  => 'application/json',
        ])->post(config('services.groq.url'), [
            'model'    => config('services.groq.model', 'llama-3.1-8b-instant'),
            'messages' => [
                [
                    'role'    => 'system',
                    'content' => 'You are a dietary compatibility analyzer. Always respond with valid JSON only.'
                ],
                [
                    'role'    => 'user',
                    'content' => $prompt
                ]
            ],
            'max_tokens'  => 300,
            'temperature' => 0.3,
        ]);

        if ($response->failed()) {
            throw new \Exception('API request failed: ' . $response->body());
        }

        // 4. extract the AI response text
        $content = $response->json('choices.0.message.content');
        
        if (!$content) {
            throw new \Exception('Empty response from API');
        }

        // 5. decode JSON from AI response
        $result = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON response: ' . json_last_error_msg());
        }

        return $result;
    }

}
