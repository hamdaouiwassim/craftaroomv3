<?php

use App\Http\Middleware\isAdmin;
use App\Http\Middleware\CanManageProducts;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\IsDesigner;
use App\Http\Middleware\IsConstructor;
use App\Http\Middleware\IsCustomer;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {


        $middleware->alias([
            'isAdmin' => isAdmin::class,
            'canManageProducts' => CanManageProducts::class,
            'role' => CheckRole::class,
            'isDesigner' => IsDesigner::class,
            'isConstructor' => IsConstructor::class,
            'isCustomer' => IsCustomer::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
