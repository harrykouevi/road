<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthFromMicroservice
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken(); // Récupère le token Authorization: Bearer ...
        
        if (!$token) {
            return response()->json(['message' => 'Token manquant'], 401);
        }
        // Appel du micro-service d'authentification
        $response = Http::withToken($token)->withHeaders(['Accept' => 'application/json']) 
            ->get('http://user-service' . '/api/check-token');

           
        if ($response->successful() && $response->json('authenticated') === true) {
            // Vérifier le token auprès de user-service
       

            // Ajouter l'utilisateur au Request
            $request->merge([
                'auth_user' => $response->json('user')
            ]);

            return $next($request); // L'utilisateur est authentifié, on continue
        }

        return response()->json(['message' => 'Non autorisé'], 401);
    }
}
