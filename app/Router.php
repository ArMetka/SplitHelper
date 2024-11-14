<?php

declare(strict_types=1);

namespace App;

use App\Attributes\Access;
use App\Attributes\Route;
use App\Enums\HttpMethod;
use App\Exceptions\RouteNotFoundException;
use App\Middleware\AccessMiddleware;

class Router
{
    private array $routes = [];

    public function __construct(private Container $container)
    {
    }

    private function register(
        HttpMethod $requestMethod,
        string $requestUri,
        array $action,
        string $access = 'default'
    ): void {
        $action[] = $access;
        $this->routes[$requestMethod->value][$requestUri] = $action;
    }

    public function get(string $requestUri, array $action): void
    {
        $this->register(HttpMethod::Get, $requestUri, $action);
    }

    public function post(string $requestUri, array $action): void
    {
        $this->register(HttpMethod::Post, $requestUri, $action);
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
                $routeAttributes = $method->getAttributes(Route::class, \ReflectionAttribute::IS_INSTANCEOF);
                $accessAttributes = $method->getAttributes(Access::class, \ReflectionAttribute::IS_INSTANCEOF);

                if (!empty($accessAttributes)) {
                    $access = (string)$accessAttributes[0]->newInstance();
                } else {
                    $access = 'default';
                }

                foreach ($routeAttributes as $routeAttribute) {
                    $routeAttribute = $routeAttribute->newInstance();
                    $this->register(
                        $routeAttribute->method,
                        $routeAttribute->path,
                        [$controller, $method->getName()],
                        $access
                    );
                }
            }
        }
    }

    public function resolve(string $requestUri, string $requestMethod): string
    {
        $route = explode('?', $requestUri)[0];
        $action = $this->routes[strtolower($requestMethod)][$route] ?? null;

        if (!$action) {
            throw new RouteNotFoundException('Requested route "' . $route . '" not found!');
        }

        [$class, $method, $access] = $action;

        if (!class_exists($class)) {
            throw new RouteNotFoundException();
        }
        $class = $this->container->get($class);

        if (!method_exists($class, $method)) {
            throw new RouteNotFoundException();
        }

        $this->container->get(AccessMiddleware::class)->process($access);

        return (string)$class->$method();
    }
}
