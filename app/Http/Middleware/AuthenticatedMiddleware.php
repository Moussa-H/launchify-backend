<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticatedMiddleware
{
  
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if(!$user){
            return response()->json([
                'message' => 'unauthorized'
           ]);
        }

        return $next($request);
    }
}
