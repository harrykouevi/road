<?php

use Illuminate\Support\Facades\Route;



// routes/web.php
use Illuminate\Support\Facades\Http;



Route::get('/login', [\App\Http\Controllers\AuthController::class, 'index'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.submit');
Route::get('/road-issues-types', [\App\Http\Controllers\RoadIssueController::class, 'getissuetypes'])->name('getissuetypes');


Route::middleware(['microauth'])->group(function () {
  
    Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/utilisateurs', [\App\Http\Controllers\UserController::class, 'index'])->name('getusers');
    Route::get('/utilisateur/{id}/edit', [\App\Http\Controllers\UserController::class, 'show'])->name('users.edit');
    Route::put('/utilisateur/{id}', [\App\Http\Controllers\UserController::class, 'update'])->name('users.update');

    Route::get('/incident-types', [\App\Http\Controllers\RoadIssueController::class, 'getissuetypes'])->name('getissuetypes');

    Route::get('/incidents', [\App\Http\Controllers\RoadIssueController::class, 'index'])->name('roadissues.index');
    Route::get('/incidents/{id}/edit', [\App\Http\Controllers\RoadIssueController::class, 'update'])->name('roadissues.edit');
    Route::get('/incidents/ajouter-nouvelle-incident', [\App\Http\Controllers\RoadIssueController::class, 'create'])->name('roadissues.create');
    Route::put('/incidents/{id}', [\App\Http\Controllers\RoadIssueController::class, 'update'])->name('roadissues.update');


    Route::get('/proxy-carte', function () {
        $response = Http::withToken(session('token'))
            ->get(env('MAP_SERVICE_URL').'/api/map-directions?start=48.857547,2.351376&end=48.866547,2.351376&alternatives=3');
        return response($response->body(), 200)
            ->header('Content-Type', 'text/html');
    })->name('proxy-carte') ;
});