<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
        $token = session('token');; // Récupère le token Authorization: Bearer ...
         Log::error('needed token') ;
        if (!$token) {
            return redirect()->route('login'); 
        }
         Log::error('there is token') ;

        // Appel du micro-service d'authentification
        $response = Http::withToken($token)->withHeaders(['Accept' => 'application/json']) 
            ->get(env('MICRO_SERVICE_AUTH_URL') . '/api/check-token');

        Log::error($response->json()) ;
        if ($response->successful() && $response->json('authenticated') === true) {
            // Vérifier le token auprès de user-service
       

            // Ajouter l'utilisateur au Request
            $request->merge([
                'auth_user' => $response->json('user')
            ]);

            return $next($request); // L'utilisateur est authentifié, on continue
        }

        return redirect()->route('login'); 
    }
}
