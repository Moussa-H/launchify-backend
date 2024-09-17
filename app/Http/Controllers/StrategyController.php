<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Strategy;
use App\Models\Startup;
use Illuminate\Support\Facades\Log;
use Http;

class StrategyController extends Controller
{
public function generateStrategies(Request $request)
{
    // Check if the user is logged in
    if (!Auth::check()) {
        return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
    }

    // Get the user's startup
    $userId = Auth::id();
    $startup = Startup::where('user_id', $userId)->firstOrFail();

    // Prepare data for AI request
    $aiRequestData = [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => 'You are a helpful assistant that generates business strategies.'],
            ['role' => 'user', 'content' => "Generate 5 strategies for a company in the {$startup->industry} industry facing these challenges: {$startup->key_challenges}. The goals are: {$startup->goals}. Each strategy should follow this structure:
1. Strategy Name: <strategy name>
Description: <brief description of the strategy>
Action Steps:
- <action step 1>
- <action step 2>
- <action step 3>
Challenges:
- <challenge 1>
- <challenge 2>"]
        ],
        'max_tokens' => 2000,
        'temperature' => 1.0,
    ];

    // Call OpenAI API
    $response = Http::withHeaders([
        'Authorization' => 'Bearer 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',
    ])->post('https://api.openai.com/v1/chat/completions', $aiRequestData);

    // Handle AI response
    if ($response->successful()) {
        $strategiesText = $response->json()['choices'][0]['message']['content'];

        // Split strategies based on the numbering pattern
        $strategiesArray = preg_split('/\n(?=\d\.)/', $strategiesText, -1, PREG_SPLIT_NO_EMPTY);

        $strategyData = [
            'startup_id' => $startup->id,
            'strategy_1_name' => null,
            'strategy_1_description' => null,
            'strategy_1_status' => 'todo',  // Default status
            'strategy_2_name' => null,
            'strategy_2_description' => null,
            'strategy_2_status' => 'todo',  // Default status
            'strategy_3_name' => null,
            'strategy_3_description' => null,
            'strategy_3_status' => 'todo',  // Default status
            'strategy_4_name' => null,
            'strategy_4_description' => null,
            'strategy_4_status' => 'todo',  // Default status
            'strategy_5_name' => null,
            'strategy_5_description' => null,
            'strategy_5_status' => 'todo',
        ];

        foreach ($strategiesArray as $index => $strategyText) {
            if ($index >= 5) break; // Limit to 5 strategies

            $strategy = $this->parseStrategy($strategyText);

            if ($strategy) {
                $strategyKey = 'strategy_' . ($index + 1);

                $strategyData[$strategyKey . '_name'] = $strategy['title'];
                $strategyData[$strategyKey . '_description'] = $strategy['description'];
            }
        }

        // Save to database
        Strategy::updateOrCreate(
            ['startup_id' => $startup->id],
            $strategyData
        );

        return response()->json([
            'message' => 'Strategies generated and saved successfully!',
            'strategies' => $strategyData
        ]);
    }

    return response()->json(['error' => 'Failed to generate strategies from AI'], 500);
}



 

   


}
