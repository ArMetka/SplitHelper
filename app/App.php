<?php

declare(strict_types=1);

namespace App;

use App\Controllers\AuthController;
use App\Controllers\AjaxController;
use App\Controllers\HomeController;
use App\Controllers\ImgController;
use App\Controllers\MeController;
use App\Controllers\RedirectController;
use App\Controllers\SplitController;
use App\Controllers\TestController;
use App\Exceptions\RouteNotFoundException;
use App\Exceptions\ViewNotFoundException;

class App
{
    private Router $router;
    private static Container $container;
    private static DB $db;

    public function __construct()
    {
        self::$container = new Container();
        self::$db = new DB((new Config($_ENV))->db);
        $this->router = new Router(self::$container);

        $this->router->registerRoutesFromAttributes([
                HomeController::class,
                AjaxController::class,
                TestController::class,
                AuthController::class,
                RedirectController::class,
                MeController::class,
                ImgController::class,
                SplitController::class,
            ]
        );
    }

    public static function db(): DB
    {
        return self::$db;
    }

    public static function container(): Container
    {
        return self::$container;
    }

    public function run(): void
    {
        try {
            echo $this->router->resolve($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
        } catch (RouteNotFoundException|ViewNotFoundException $e) {
            http_response_code(404);
            echo View::make('errors/404', ['exception' => $e]);
        } catch (\Throwable $e) {
            http_response_code(503);
            echo View::make('errors/503', ['exception' => $e]);
        }
    }
}