<?php

use App\Exceptions\CannotStoreUrlException;
use App\Http\Middleware\ForceJson;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(append: [ForceJson::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (CannotStoreUrlException $e) {
            return new JsonResponse('Temporarily unavailable', Response::HTTP_SERVICE_UNAVAILABLE);
        });
    })->create();
