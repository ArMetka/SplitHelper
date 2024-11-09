<?php

declare(strict_types=1);

namespace App;

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\RedirectController;
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
                TestController::class,
                AuthController::class,
                RedirectController::class,
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
            echo View::make('errors/404');
            echo 'Exception occurred on line ' . $e->getLine() . ' in file "' . $e->getFile() . '"<br>';
            echo 'Message: ' . $e->getMessage();
        } catch (\Throwable $e) {
            echo View::make('errors/503');
            echo 'Exception occurred on line ' . $e->getLine() . ' in file "' . $e->getFile() . '"<br>';
            echo 'Message: ' . $e->getMessage();
        }
    }
}