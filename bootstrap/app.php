<?php

require_once __DIR__.'/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();


$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);


$app->configure('validation');
$app->configure('response');


$app->withEloquent();


$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);


$app->middleware([
    App\Http\Middleware\CorsMiddleware::class
]);

$app->routeMiddleware([
    'auth.user' => App\Http\Middleware\AuthMiddleware::class,
    'auth.admin' => App\Http\Middleware\AuthAdminMiddleware::class,
    'demo' => App\Http\Middleware\DemoMiddleware::class,
    'admin.enabled' => App\Http\Middleware\AdminEnabledMiddleware::class
]);


$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/web.php';
});

return $app;
