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
        // Registra il middleware per il cambio lingua
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
        
        // Registra middleware personalizzati per sicurezza
        $middleware->alias([
            'config.access' => \App\Http\Middleware\ConfigurationAccess::class,
            'permissions' => \App\Http\Middleware\CheckUserPermissions::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();