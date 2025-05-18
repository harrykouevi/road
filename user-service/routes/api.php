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
}); // route permettant a docker de verifier que le service issue de cette application a bien demarrer



// Route pour l'enregistrement d'un nouvel utilisateur
// Cette route prend les informations de l'utilisateur (nom, email, mot de passe) et crée un nouvel utilisateur
Route::post('/register', [AuthController::class, 'register']); //route d'inscription

// Route pour la connexion de l'utilisateur
Route::post('/login', [AuthController::class, 'login']); //route d'authentification

Route::get('/stats', [UserController::class, 'stats']);


// Routes protégées nécessitant une authentification via token (auth:sanctum)
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/check-token', [AuthController::class, 'check']); //route permettant aux autre micro service de savoir si l'utilisateur est authentifié

    
    // Route pour obtenir le profil de l'utilisateur connecté
    // Cette route est accessible uniquement si l'utilisateur est authentifié avec un token valide
    Route::get('/all-users', [UserController::class, 'index']); //route recuperant tous les utilisateurs

    Route::get('/profile', [UserController::class, 'profile']); // route permetant de recuperer utilisateur connecté grace au token
    
    // Route pour mettre à jour les informations du profil utilisateur
    // Cette route est également protégée par un token d'authentification
    Route::put('/profile/update', [UserController::class, 'updateProfile']); // route permettant la mis a jour des informations d'un utilisateur connecté
    
    // Route pour déconnecter l'utilisateur
    // Cela supprime le token actuel de l'utilisateur, mettant fin à sa session
    Route::post('/logout', [AuthController::class, 'logout']) ; // route de deconnexion d'un utilisateur;

    Route::get('/users/{id}', [UserController::class, 'show']); // route permetant de recuperer utilisateur connecté grace au token
     // Route pour mettre à jour les informations  utilisateur
    // Cette route est également protégée par un token d'authentification
    Route::put('/users/{id}', [UserController::class, 'update']); // route permettant la mis a jour des informations d'un utilisateur connecté
    
   

}); //toutes les routes necessitant une authenthification

