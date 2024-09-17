<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investment;
use App\Models\Investor;
use App\Models\Startup;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Auth;

class InvestmentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET')); // Set Stripe API key from environment
    }

    public function createInvestment(Request $request)
    {

         // Check if the user is authenticated
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Get the authenticated user's ID
        $userId = Auth::id();

        // Fetch the startup for the authenticated user, throw 404 if not found
        $investor = Investor::where('user_id', $userId)->firstOrFail();

         $validatedData['investor_id'] = $investor->id;
        $request->validate([
            'startup_id' => 'required|exists:startups,id',
            'amount' => 'required|integer|min:100', // Minimum 1 dollar
            'payment_method_id' => 'required|string',
        ]);

      
        $startup = Startup::findOrFail($request->startup_id);

        try {
            // Create a PaymentIntent with automatic payment methods enabled and no redirects allowed
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount * 100, // Amount in cents
                'currency' => 'usd',
                'payment_method' => $request->payment_method_id,
                'confirm' => true,
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never'
                ],
            ]);

            // Check if additional action is required
            if ($paymentIntent->status === 'requires_action' || $paymentIntent->status === 'requires_source_action') {
                return response()->json([
                    'requires_action' => true,
                    'client_secret' => $paymentIntent->client_secret
                ]);
            }

            if ($paymentIntent->status === 'succeeded') {
                $investment = Investment::create([
                    'investor_id' => $investor->id,
                    'startup_id' => $startup->id,
                    'amount' => $request->amount,
                ]);

                return response()->json(['success' => true, 'investment' => $investment], 201);
            }

            return response()->json(['error' => 'Payment failed'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
      public function getStartupInvestmentSum(Request $request)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Get authenticated user's ID
        $userId = Auth::id();

        // Fetch the investor associated with the authenticated user
        $investor = Investor::where('user_id', $userId)->firstOrFail();

        // Request validation to ensure the startup ID is provided
        $request->validate([
            'startup_id' => 'required|exists:startups,id',
        ]);

        // Fetch the startup by the given startup ID
        $startup = Startup::findOrFail($request->startup_id);

        // Calculate the total sum of investments for this startup
        $totalInvestment = Investment::where('startup_id', $startup->id)
            ->sum('amount'); // Sum the amount column

        // Return the result as a JSON response
        return response()->json([
            'success' => true,
            'total_investment' => $totalInvestment,
        ]);
    }

  

}
