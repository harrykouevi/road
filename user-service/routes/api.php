<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|---------------------------------------------------------------------------
| API Routes
|---------------------------------------------------------------------------
|
| Ici, vous pouvez enregistrer les routes API pour votre application. Ces
| routes sont chargées par le RouteServiceProvider dans un groupe qui
| est assigné au groupe middleware "api". Amusez-vous à construire votre API !
|
*/


Route::get('/', function(){
    return  response()->json(['status' => 'ok', 'service' => 'utilisateur' ]);
});



// Route pour l'enregistrement d'un nouvel utilisateur
// Cette route prend les informations de l'utilisateur (nom, email, mot de passe) et crée un nouvel utilisateur
Route::post('/register', [AuthController::class, 'register']);

// Route pour la connexion de l'utilisateur
Route::post('/login', [AuthController::class, 'login']);


// Routes protégées nécessitant une authentification via token (auth:sanctum)
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/check-token', [AuthController::class, 'check']);

    
    // Route pour obtenir le profil de l'utilisateur connecté
    // Cette route est accessible uniquement si l'utilisateur est authentifié avec un token valide
    Route::get('/all-users', [UserController::class, 'index']);

    Route::get('/profile', [UserController::class, 'profile']);
    
    // Route pour mettre à jour les informations du profil utilisateur
    // Cette route est également protégée par un token d'authentification
    Route::put('/profile/update', [UserController::class, 'updateProfile']);
    
    // Route pour déconnecter l'utilisateur
    // Cela supprime le token actuel de l'utilisateur, mettant fin à sa session
    Route::post('/logout', [AuthController::class, 'logout']);
});
