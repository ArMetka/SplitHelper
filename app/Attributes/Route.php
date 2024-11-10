<?php

namespace App\Attributes;

use App\Enums\HttpMethod;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
abstract class Route
{
    public function __construct(
        public readonly string $path,
        public readonly HttpMethod $method
    ) {
    }
}