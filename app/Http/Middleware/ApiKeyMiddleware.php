<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-KEY');

        // Vérification de la clé API
        if (!$apiKey || $apiKey !== env('API_SECRET_KEY')) {
            return response()->json([
                'success' => false,
                'message' => 'Accès refusé. Clé API manquante ou invalide.'
            ], 401);
        }

        return $next($request);
    }
}
