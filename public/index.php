<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Controllers\TestController;

require __DIR__ . '/../vendor/autoload.php';

const VIEWS_PATH = __DIR__ . '/../views';

Dotenv\Dotenv::createImmutable(__DIR__ . '/..')->load();

$router = new \App\Router();
$router->registerRoutesFromAttributes([
        HomeController::class,
        TestController::class
    ]
);
echo '<pre>';
var_dump($router->routes());
echo '</pre>';

//$router
//    ->get('/', [\App\Controllers\HomeController::class, 'index']);

//try {
//    echo $router->resolve($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
//} catch (\App\Exceptions\RouteNotFoundException|\App\Exceptions\ViewNotFoundException $e) {
//    echo \App\View::make('errors/404')->render();
//}
echo $router->resolve($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
