<?php

use App\Http\Middleware\AdminMIddleware;
use App\Http\Middleware\EnforceJsonResponseMIddleware;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => AdminMIddleware::class,
            'json_response' => EnforceJsonResponseMIddleware::class
        ]);
    })
    
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (NotFoundHttpException $notFoundHttpException, Request $request) {
            return $request->is('api/*')
                ? response()->json(['message' => $notFoundHttpException->getMessage()], 404)
                : $notFoundHttpException->getMessage();
        });

        $exceptions->render(function (Exception $exception, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $exception->getMessage()
                ]);
            }
            return $exception->getMessage();
        });
    })->create();
