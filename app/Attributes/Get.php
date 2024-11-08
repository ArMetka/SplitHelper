<?php

namespace App\Attributes;

use App\Enums\HttpMethod;
use Attribute;

#[Attribute]
class Get extends Route
{
    public function __construct(string $path)
    {
        parent::__construct($path, HttpMethod::Get);
    }
}