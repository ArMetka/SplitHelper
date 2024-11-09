<?php

namespace App\Attributes;

use App\Enums\HttpMethod;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
abstract class Route
{
    public function __construct(public string $path, public HttpMethod $method)
    {
    }
}