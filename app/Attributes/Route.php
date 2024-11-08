<?php

namespace App\Attributes;

use App\Enums\HttpMethod;
use Attribute;

#[Attribute]
abstract class Route
{
    public function __construct(public string $path, public HttpMethod $method)
    {
    }
}