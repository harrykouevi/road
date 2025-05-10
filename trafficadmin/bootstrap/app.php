<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // D'abord déclarer ton middleware personnalisé
        $middleware->alias(['microauth'=> App\Http\Middleware\CheckAuthFromMicroservice::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
