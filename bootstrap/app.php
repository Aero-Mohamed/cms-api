<?php

use App\Http\Handlers\CommonApiExceptionHandler;
use App\Http\Middleware\AlwaysAcceptJson;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use League\OAuth2\Server\Exception\OAuthServerException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->prependToGroup('api', AlwaysAcceptJson::class);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->dontReport([
            // Thrown in case the given Access Token is invalid
            OAuthServerException::class,
        ]);

        // Handle API Exceptions
        (new CommonApiExceptionHandler())->handler($exceptions);
    })->create();
