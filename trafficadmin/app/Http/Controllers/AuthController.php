<?php

// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;


class AuthController
{
    public function index()
    {
   
        return view('login');
    }

    public function login(Request $request)
    {
        
        $response = Http::post(env('MICRO_SERVICE_AUTH_URL').'/api/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

      

        if ($response->successful()) {
            $user = $response->json()['data']['user'];
            $token = $response->json()['data']['token'];

            // Stocker en session ou autre
            session([
                'user' => $user,
                'token' => $token,
            ]);

            return redirect()->route('dashboard');
        }

        // Erreurs personnalisées du microservice
        $responseBody = $response->json();
        $errors = [];

        if (isset($responseBody['message']) && is_array($responseBody['message'])) {
            foreach ($responseBody['message'] as $field => $messages) {
                $errors[$field] = $messages[0]; // Prend le 1er message pour chaque champ
            }
        }

        return redirect()->route('login')->withInput()->with('error', 'Identifiants invalides')->withErrors($errors);;
    }

  

    public function logout(Request $request)
    {
        $token = session('token');

        // 1. Appeler le microservice pour invalider le token (si nécessaire)
        if ($token) {
            Http::withToken($token)->post(env('MICRO_SERVICE_AUTH_URL') . '/api/logout');
        }

        // 2. Supprimer la session Laravel
        Session::forget(['user', 'token']);
        Session::flush();

        // 3. Redirection
        return redirect()->route('login')->with('success', 'Déconnexion réussie.');
    }

}
