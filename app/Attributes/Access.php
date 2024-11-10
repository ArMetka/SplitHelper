<?php

namespace App\Attributes;

use App\Exceptions\InvalidArgumentsException;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
abstract class Access
{
    public function __construct(
        public readonly bool $secure_only,
        public readonly bool $guest_only
    ) {
        if ($this->secure_only && $this->guest_only) {
            throw new InvalidArgumentsException();
        }
    }

    public function __toString(): string
    {
        return ($this->secure_only) ? 'secure' : 'guest';
    }
}