<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Strategy;
use App\Models\Startup;
use Http;

class StrategyController extends Controller
{
    /**
     * Fetch and store AI-generated strategies for a specific startup.
     */
   public function generateStrategies(Request $request)
{
    // Check if the user is authenticated
    if (!Auth::check()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }

    // Get the authenticated user's ID
    $userId = Auth::id();

    // Fetch the user's startup, throw 404 if not found
    $startup = Startup::where('user_id', $userId)->firstOrFail();

    // Prepare the request body for AI strategy generation
    $aiRequestData = [
        'model' => 'gpt-3.5-turbo',  // Specify the model you're using
        'messages' => [
            ['role' => 'system', 'content' => 'You are a helpful assistant that generates business strategies.'],
            ['role' => 'user', 'content' => "Generate 2 strategies for a company in the {$startup->industry} industry. The company is facing the following key challenges: {$startup->key_challenges}. The goals of the company are: {$startup->goals}. Provide actionable steps and potential challenges for each strategy."],
        ],
        'max_tokens' => 1000,  // Adjust token limit as needed
        'temperature' => 1.0,  // Adjust temperature (0.7 is a good balance between creativity and precision)
    ];

    // Send request to OpenAI API
    $response = Http::withHeaders([
        'Authorization' => 'Bearer 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',
    ])->post('https://api.openai.com/v1/chat/completions', $aiRequestData);

    // Check if the request was successful
    if ($response->successful()) {
        $strategies = $response->json()['choices'][0]['message']['content']; // Fetching the AI-generated content

        // Assuming the AI returns a structured list of strategies, you may need to parse this
        $strategies = explode("\n\n", $strategies); // Split based on newlines or another pattern

        // Save each strategy into the database
        foreach ($strategies as $strategyData) {
            Strategy::create([
                'startup_id' => $startup->id,
                'title' => 'Generated Strategy', // You can refine the title if needed
                'description' => $strategyData,
                'actionable_steps' => 'Steps to be parsed', // Placeholder; parse as per the response format
                'potential_challenges' => 'Challenges to be parsed', // Placeholder; parse as per the response format
            ]);
        }

        return response()->json(['message' => 'Strategies generated and saved successfully!']);
    } else {
        return response()->json(['error' => 'Failed to generate strategies from AI'], 500);
    }
}


}
