<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // D'abord déclarer ton middleware personnalisé
        $middleware->alias(['microauth'=> App\Http\Middleware\CheckAuthFromMicroservice::class]);

        // Configuration des middlewares pour les routes API
        $middleware->group('api', [
            
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, // Pour les SPA, mobile, etc.
            \Illuminate\Routing\Middleware\SubstituteBindings::class, // Permet lier les paramètres des routes
            // Ajoute d'autres middlewares si nécessaire (ex. Sanctum)
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
