<?php

declare(strict_types=1);

namespace App;

/**
 * @property array db
 */
class Config
{
    protected array $config = [];

    public function __construct(array $env)
    {
        $this->config = [
            'db' => [
                'host' => $env['DB_HOST'],
                'user' => $env['DB_USER'],
                'pass' => $env['DB_PASS'],
                'database' => $env['DB_DATABASE'],
                'driver' => $env['DB_DRIVER'] ?? 'pgsql',
            ]
        ];
    }

    public function __get($name): mixed
    {
        return $this->config[$name] ?? null;
    }
}