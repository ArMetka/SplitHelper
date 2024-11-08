<?php

declare(strict_types=1);

namespace App;

use App\Attributes\Route;
use App\Exceptions\RouteNotFoundException;

class Router
{
    private array $routes = [];

    private function register(string $requestMethod, string $requestUri, array $action): void
    {
        $this->routes[$requestMethod][$requestUri] = $action;
    }

    public function get(string $requestUri, array $action): void
    {
        $this->register('get', $requestUri, $action);
    }

    public function post(string $requestUri, array $action): void
    {
        $this->register('post', $requestUri, $action);
    }

    public function routes(): array
    {
        return $this->routes;
    }

    public function registerRoutesFromAttributes(array $controllers): void
    {
        foreach ($controllers as $controller) {
            $class = new \ReflectionClass($controller);

            foreach ($class->getMethods() as $method) {
                $attributes = $method->getAttributes(Route::class, \ReflectionAttribute::IS_INSTANCEOF);

                foreach ($attributes as $attribute) {
                    $attribute = $attribute->newInstance();
                    $this->register($attribute->method->value, $attribute->path, [$controller, $method->getName()]);
                }
            }

        }
    }

    public function resolve(string $requestUri, string $requestMethod): string
    {
        $route = explode('?', $requestUri)[0];
        $action = $this->routes[strtolower($requestMethod)][$route];

        if (!$action) {
            throw new RouteNotFoundException();
        }

        [$class, $method] = $action;

        if (!class_exists($class)) {
            throw new RouteNotFoundException();
        }
        $class = new $class();

        if (!method_exists($class, $method)) {
            throw new RouteNotFoundException();
        }

        return (string)$class->$method();
    }
}
