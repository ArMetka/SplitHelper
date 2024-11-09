<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\ContainerException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $entries = [];

    public function get(string $id)
    {
        if ($this->has($id)) {
            $entry = $this->entries[$id];

            return $entry($this);
        }

        return $this->resolve($id);
    }

    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }

    public function set(string $id, callable $concrete): void
    {
        $this->entries[$id] = $concrete;
    }

    private function resolve(string $id)
    {
        $reflectionClass = new \ReflectionClass($id);

        if (!$reflectionClass->isInstantiable()) {
            throw new ContainerException('class "' . $id . '" is not instantiable!');
        }

        $constructor = $reflectionClass->getConstructor();

        if (!$constructor) {
            return new $id;
        }

        $parameters = $constructor->getParameters();

        if (!$parameters) {
            return new $id;
        }

        $dependencies = array_map(function (\ReflectionParameter $param) use ($id) {
            $name = $param->getName();
            $type = $param->getType();

            if (!$type) {
                throw new ContainerException(
                    'Failed to resolve class "' . $id . '": param "' . $name . '" is not type-hinted!'
                );
            }

            if ($type instanceof \ReflectionUnionType || $type->isBuiltin()) {
                throw new ContainerException(
                    'Failed to resolve class "' . $id . '": param of type "' . $type . '" is unsupported!'
                );
            }

            if ($type instanceof \ReflectionNamedType) {
                return $this->get($type->getName());
            }

            throw new ContainerException('Failed to resolve class "' . $id . '"');
        }, $parameters);

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}