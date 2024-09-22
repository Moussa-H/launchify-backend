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
        'max_tokens' => 1000,
        'temperature' => 1.0,
    ];

    // Call OpenAI API
  $response = Http::withHeaders([
    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
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

private function parseStrategy($strategyText)
{
    // Use regex to extract title and description
    preg_match('/Strategy Name:\s*(.+?)\nDescription:\s*(.+?)\nAction Steps:\n((?:- .*\n?)+?)\nChallenges:\n((?:- .*\n?)*)/s', $strategyText, $matches);

    if (count($matches) >= 5) {
        $description = trim($matches[2]) . "\nAction Steps:\n" . trim($matches[3]) . "\nChallenges:\n" . trim($matches[4]);

        return [
            'title' => trim($matches[1]),
            'description' => $description,
        ];
    }

    Log::warning('Failed to parse strategy:', ['text' => $strategyText]);
    return null; // Return null if the format does not match
}


 

    public function getStrategy()
    {
        // Check if the user is logged in
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        // Get the authenticated user's startup
        $userId = Auth::id();
        $startup = Startup::where('user_id', $userId)->first();

        if (!$startup) {
            return response()->json(['status' => 'error', 'message' => 'Startup not found'], 404);
        }

        // Retrieve the strategies for the startup
        $strategy = Strategy::where('startup_id', $startup->id)->first();

        if (!$strategy) {
            return response()->json(['status' => 'error', 'message' => 'No strategies found for this startup.'], 404);
        }

        // Return the strategy details
        return response()->json([
            'status' => 'success',
            'message' => 'Strategy retrieved successfully!',
            'strategy' => [
                'startup_id' => $strategy->startup_id,
                'strategy_1_name' => $strategy->strategy_1_name,
                'strategy_1_description' => $strategy->strategy_1_description,
                'strategy_1_description' => $strategy->strategy_1_description,
                'strategy_2_name' => $strategy->strategy_2_name,
                'strategy_2_description' => $strategy->strategy_2_description,
                'strategy_3_name' => $strategy->strategy_3_name,
                'strategy_3_description' => $strategy->strategy_3_description,
                'strategy_4_name' => $strategy->strategy_4_name,
                'strategy_4_description' => $strategy->strategy_4_description,
                'strategy_5_name' => $strategy->strategy_5_name,
                'strategy_5_description' => $strategy->strategy_5_description,
                 'strategy_1_status' => $strategy->strategy_1_status,
                 'strategy_2_status' => $strategy->strategy_2_status,
                'strategy_3_status' => $strategy->strategy_3_status,
                  'strategy_4_status' => $strategy->strategy_4_status,
                 'strategy_5_status' => $strategy->strategy_5_status,
            ]
        ]);
    }
public function updateStatuses(Request $request)
{
    // Check if the user is logged in
    if (!Auth::check()) {
        return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
    }

     $userId = Auth::id();
        $startup = Startup::where('user_id', $userId)->first();

        if (!$startup) {
            return response()->json(['status' => 'error', 'message' => 'Startup not found'], 404);
        }
    // Validate the request
    $request->validate([
        'strategy_1_status' => 'nullable|in:todo,in progress,completed',
        'strategy_2_status' => 'nullable|in:todo,in progress,completed',
        'strategy_3_status' => 'nullable|in:todo,in progress,completed',
        'strategy_4_status' => 'nullable|in:todo,in progress,completed',
        'strategy_5_status' => 'nullable|in:todo,in progress,completed',
    ]);

    // Get the startup by ID
  

    // Find the strategy record for the given startup
    $strategy = Strategy::where('startup_id', $startup->id);

    if (!$strategy) {
        return response()->json(['status' => 'error', 'message' => 'No strategies found for this startup.'], 404);
    }

    // Prepare data to update statuses
    $updateData = [];
    foreach (['strategy_1', 'strategy_2', 'strategy_3', 'strategy_4', 'strategy_5'] as $strategyNumber) {
        $statusKey = "{$strategyNumber}_status";
        if ($request->has($statusKey)) {
            $updateData[$statusKey] = $request->input($statusKey);
        }
    }

    // Update the strategy with the new statuses
    $strategy->update($updateData);

    return response()->json([
        'status' => 'success',
        'message' => 'Strategy statuses updated successfully!',
        'strategy' => $strategy
    ], 200);
}

}
